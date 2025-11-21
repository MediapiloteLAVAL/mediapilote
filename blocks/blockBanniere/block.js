(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const { PanelBody, TextControl, ColorPalette, SelectControl, Button } =
    wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/banniere", {
    title: __("Bloc Bannière", "mediapilote"),
    icon: "format-image",
    category: "design",
    keywords: [
      __("bannière", "mediapilote"),
      __("hero", "mediapilote"),
      __("titre", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const {
        backgroundType,
        backgroundColor,
        backgroundImage,
        description,
        buttonText,
        buttonUrl,
        textColor,
      } = attributes;

      // Récupérer le titre de la page pour l'affichage dans l'éditeur
      const pageTitle = wp.data.select("core/editor")
        ? wp.data.select("core/editor").getEditedPostAttribute("title") ||
          "Titre de la page"
        : "Titre de la page";

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(
          InspectorControls,
          null,
          wp.element.createElement(
            PanelBody,
            {
              title: __("Arrière-plan", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(SelectControl, {
              label: __("Type d'arrière-plan", "mediapilote"),
              value: backgroundType,
              options: [
                { label: __("Couleur unie", "mediapilote"), value: "color" },
                { label: __("Image", "mediapilote"), value: "image" },
              ],
              onChange: function (value) {
                setAttributes({ backgroundType: value });
              },
            }),
            backgroundType === "color" &&
              wp.element.createElement(
                "div",
                { className: "components-base-control" },
                wp.element.createElement(
                  "label",
                  { className: "components-base-control__label" },
                  __("Couleur de fond", "mediapilote")
                ),
                wp.element.createElement(ColorPalette, {
                  value: backgroundColor,
                  onChange: function (color) {
                    setAttributes({ backgroundColor: color });
                  },
                })
              ),
            backgroundType === "image" &&
              wp.element.createElement(
                MediaUploadCheck,
                null,
                wp.element.createElement(
                  "div",
                  { className: "components-base-control" },
                  wp.element.createElement(
                    "label",
                    { className: "components-base-control__label" },
                    __("Image de fond", "mediapilote")
                  ),
                  backgroundImage &&
                    wp.element.createElement(
                      "div",
                      { style: { marginBottom: "10px" } },
                      wp.element.createElement("img", {
                        src: backgroundImage.url,
                        alt: backgroundImage.alt || "",
                        style: {
                          width: "100%",
                          height: "auto",
                          maxHeight: "150px",
                          objectFit: "cover",
                        },
                      })
                    ),
                  wp.element.createElement(MediaUpload, {
                    onSelect: function (media) {
                      setAttributes({
                        backgroundImage: {
                          id: media.id,
                          url: media.url,
                          alt: media.alt,
                        },
                      });
                    },
                    allowedTypes: ["image"],
                    value: backgroundImage ? backgroundImage.id : "",
                    render: function (obj) {
                      return wp.element.createElement(
                        Button,
                        {
                          onClick: obj.open,
                          variant: backgroundImage ? "secondary" : "primary",
                        },
                        backgroundImage
                          ? __("Changer l'image", "mediapilote")
                          : __("Sélectionner une image", "mediapilote")
                      );
                    },
                  }),
                  backgroundImage &&
                    wp.element.createElement(
                      Button,
                      {
                        onClick: function () {
                          setAttributes({ backgroundImage: null });
                        },
                        variant: "link",
                        isDestructive: true,
                        style: { marginTop: "10px" },
                      },
                      __("Supprimer l'image", "mediapilote")
                    )
                )
              )
          ),
          wp.element.createElement(
            PanelBody,
            {
              title: __("Contenu", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(
              "div",
              { className: "components-base-control" },
              wp.element.createElement(
                "label",
                { className: "components-base-control__label" },
                __("Titre", "mediapilote")
              ),
              wp.element.createElement(
                "p",
                { style: { fontStyle: "italic", color: "#666" } },
                __(
                  "Le titre affiché sera automatiquement celui de la page",
                  "mediapilote"
                )
              )
            ),
            wp.element.createElement(TextControl, {
              label: __("Description (optionnelle)", "mediapilote"),
              value: description,
              onChange: function (value) {
                setAttributes({ description: value });
              },
              help: __(
                "Texte affiché entre le titre et le bouton",
                "mediapilote"
              ),
            }),
            wp.element.createElement(TextControl, {
              label: __("Texte du bouton", "mediapilote"),
              value: buttonText,
              onChange: function (value) {
                setAttributes({ buttonText: value });
              },
            }),
            wp.element.createElement(TextControl, {
              label: __("URL du bouton", "mediapilote"),
              value: buttonUrl,
              onChange: function (value) {
                setAttributes({ buttonUrl: value });
              },
            })
          ),
          wp.element.createElement(
            PanelBody,
            {
              title: __("Style du texte", "mediapilote"),
              initialOpen: false,
            },
            wp.element.createElement(
              "div",
              { className: "components-base-control" },
              wp.element.createElement(
                "label",
                { className: "components-base-control__label" },
                __("Couleur du texte", "mediapilote")
              ),
              wp.element.createElement(ColorPalette, {
                value: textColor,
                onChange: function (color) {
                  setAttributes({ textColor: color });
                },
              })
            )
          )
        ),
        wp.element.createElement(
          "div",
          {
            className:
              className +
              " wp-block-mediapilote-banniere banniere-section alignfull",
            style: {
              backgroundColor:
                backgroundType === "color" ? backgroundColor : "transparent",
              backgroundImage:
                backgroundType === "image" && backgroundImage
                  ? `url(${backgroundImage.url})`
                  : "none",
              backgroundSize: "cover",
              backgroundPosition: "center",
              backgroundRepeat: "no-repeat",
              position: "relative",
            },
          },
          backgroundType === "image" &&
            backgroundImage &&
            wp.element.createElement("div", {
              className: "banniere-section__overlay",
            }),
          wp.element.createElement(
            "div",
            { className: "container-fluid" },
            wp.element.createElement(
              "div",
              {
                className: "banniere-section__content",
                style: { color: textColor },
              },
              wp.element.createElement(
                "h1",
                { className: "banniere-section__title" },
                pageTitle
              ),
              description &&
                wp.element.createElement(
                  "div",
                  { className: "banniere-section__description" },
                  wp.element.createElement("p", null, description)
                ),
              buttonText &&
                wp.element.createElement(
                  "a",
                  {
                    href: buttonUrl,
                    className: "btn",
                    style: {
                      borderColor: textColor,
                      color: textColor,
                    },
                  },
                  wp.element.createElement(
                    "span",
                    { className: "btn-text" },
                    buttonText
                  )
                )
            )
          )
        )
      );
    },

    save: function () {
      // Rendu côté serveur via PHP
      return null;
    },
  });
})(window.wp);
