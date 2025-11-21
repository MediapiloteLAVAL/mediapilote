(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { RichText, InspectorControls, MediaUpload, MediaUploadCheck } =
    wp.blockEditor;
  const {
    PanelBody,
    TextControl,
    TextareaControl,
    ColorPalette,
    Button,
    Dashicon,
  } = wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/slider-confiance", {
    title: __("Slider Confiance", "mediapilote"),
    icon: "slides",
    category: "design",
    keywords: [
      __("confiance", "mediapilote"),
      __("slider", "mediapilote"),
      __("partenaires", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const { title, description, backgroundColor, textColor, items } =
        attributes;

      // Fonction pour ajouter un nouvel item
      const addItem = () => {
        const newItems = [
          ...items,
          { image: "", subtitle: "", description: "" },
        ];
        setAttributes({ items: newItems });
      };

      // Fonction pour supprimer un item
      const removeItem = (index) => {
        const newItems = items.filter((_, i) => i !== index);
        setAttributes({ items: newItems });
      };

      // Fonction pour mettre à jour un item
      const updateItem = (index, field, value) => {
        const newItems = [...items];
        newItems[index] = { ...newItems[index], [field]: value };
        setAttributes({ items: newItems });
      };

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(
          InspectorControls,
          null,
          wp.element.createElement(
            PanelBody,
            {
              title: __("Contenu", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(TextControl, {
              label: __("Titre", "mediapilote"),
              value: title,
              onChange: (value) => setAttributes({ title: value }),
            }),
            wp.element.createElement(TextareaControl, {
              label: __("Description", "mediapilote"),
              value: description,
              onChange: (value) => setAttributes({ description: value }),
              placeholder: __("Description optionnelle...", "mediapilote"),
            })
          ),
          wp.element.createElement(
            PanelBody,
            {
              title: __("Items du slider", "mediapilote"),
              initialOpen: true,
            },
            items.map((item, index) =>
              wp.element.createElement(
                "div",
                {
                  key: index,
                  className: "slider-item-control",
                  style: {
                    marginBottom: "20px",
                    padding: "15px",
                    border: "1px solid #ddd",
                    borderRadius: "5px",
                  },
                },
                wp.element.createElement(
                  "div",
                  {
                    style: {
                      display: "flex",
                      justifyContent: "space-between",
                      alignItems: "center",
                      marginBottom: "10px",
                    },
                  },
                  wp.element.createElement(
                    "strong",
                    null,
                    __("Item ", "mediapilote") + (index + 1)
                  ),
                  wp.element.createElement(
                    Button,
                    {
                      isDestructive: true,
                      isSmall: true,
                      onClick: () => removeItem(index),
                    },
                    __("Supprimer", "mediapilote")
                  )
                ),
                wp.element.createElement(
                  MediaUploadCheck,
                  null,
                  wp.element.createElement(MediaUpload, {
                    onSelect: (media) => updateItem(index, "image", media.url),
                    allowedTypes: ["image"],
                    value: item.image,
                    render: ({ open }) =>
                      wp.element.createElement(
                        "div",
                        { style: { marginBottom: "10px" } },
                        wp.element.createElement(
                          "label",
                          { className: "components-base-control__label" },
                          __("Image", "mediapilote")
                        ),
                        item.image
                          ? wp.element.createElement(
                              "div",
                              null,
                              wp.element.createElement("img", {
                                src: item.image,
                                style: {
                                  maxWidth: "100%",
                                  height: "auto",
                                  marginBottom: "10px",
                                },
                              }),
                              wp.element.createElement(
                                Button,
                                {
                                  onClick: open,
                                  isSecondary: true,
                                },
                                __("Changer l'image", "mediapilote")
                              )
                            )
                          : wp.element.createElement(
                              Button,
                              {
                                onClick: open,
                                isPrimary: true,
                              },
                              __("Sélectionner une image", "mediapilote")
                            )
                      ),
                  })
                ),
                wp.element.createElement(TextControl, {
                  label: __("Sous-titre", "mediapilote"),
                  value: item.subtitle,
                  onChange: (value) => updateItem(index, "subtitle", value),
                  placeholder: __("Sous-titre optionnel...", "mediapilote"),
                }),
                wp.element.createElement(TextareaControl, {
                  label: __("Description", "mediapilote"),
                  value: item.description,
                  onChange: (value) => updateItem(index, "description", value),
                  placeholder: __("Description optionnelle...", "mediapilote"),
                })
              )
            ),
            wp.element.createElement(
              Button,
              {
                isPrimary: true,
                onClick: addItem,
              },
              __("Ajouter un item", "mediapilote")
            )
          ),
          wp.element.createElement(
            PanelBody,
            {
              title: __("Style", "mediapilote"),
              initialOpen: false,
            },
            wp.element.createElement(
              "div",
              { className: "components-base-control" },
              wp.element.createElement(
                "label",
                { className: "components-base-control__label" },
                __("Couleur d'arrière-plan", "mediapilote")
              ),
              wp.element.createElement(ColorPalette, {
                value: backgroundColor,
                onChange: (value) =>
                  setAttributes({ backgroundColor: value || "#d9d9d9" }),
              })
            ),
            wp.element.createElement(
              "div",
              {
                className: "components-base-control",
                style: { marginTop: "20px" },
              },
              wp.element.createElement(
                "label",
                { className: "components-base-control__label" },
                __("Couleur du texte", "mediapilote")
              ),
              wp.element.createElement(ColorPalette, {
                value: textColor,
                onChange: (value) =>
                  setAttributes({ textColor: value || "#2d3037" }),
              })
            )
          )
        ),
        wp.element.createElement(
          "div",
          {
            className:
              className + " wp-block-mediapilote-slider-confiance alignfull",
            style: {
              backgroundColor: backgroundColor,
              color: textColor,
              padding: "60px 0",
            },
          },
          wp.element.createElement(
            "div",
            { className: "slider-confiance-section__wrapper" },
            wp.element.createElement(
              "div",
              { className: "slider-confiance-container" },
              wp.element.createElement(
                "div",
                { className: "slider-confiance-content" },
                title &&
                  wp.element.createElement(
                    "h2",
                    {
                      className: "slider-confiance-title",
                      style: {
                        fontSize: "70px",
                        marginBottom: "20px",
                        color: textColor,
                      },
                    },
                    title
                  ),
                description &&
                  wp.element.createElement(
                    "p",
                    {
                      className: "slider-confiance-description",
                      style: {
                        fontSize: "18px",
                        lineHeight: "28px",
                        marginBottom: "40px",
                        color: textColor,
                      },
                    },
                    description
                  ),
                wp.element.createElement(
                  "div",
                  { className: "slider-confiance-preview" },
                  wp.element.createElement(
                    "p",
                    {
                      style: {
                        fontSize: "16px",
                        fontStyle: "italic",
                        marginBottom: "20px",
                      },
                    },
                    __(
                      "Aperçu du slider - Les items seront affichés ici sur le frontend.",
                      "mediapilote"
                    )
                  ),
                  wp.element.createElement(
                    "div",
                    {
                      style: { display: "flex", gap: "20px", flexWrap: "wrap" },
                    },
                    items.map((item, index) =>
                      wp.element.createElement(
                        "div",
                        {
                          key: index,
                          style: {
                            width: "300px",
                            padding: "20px",
                            border: "1px solid #707070",
                            backgroundColor: "transparent",
                            color: "inherit",
                            textAlign: "left",
                          },
                        },
                        item.image
                          ? wp.element.createElement("img", {
                              src: item.image,
                              style: {
                                width: "100%",
                                height: "150px",
                                objectFit: "contain",
                                marginBottom: "10px",
                                alignSelf: "center",
                              },
                            })
                          : wp.element.createElement(
                              "div",
                              {
                                style: {
                                  width: "100%",
                                  height: "150px",
                                  backgroundColor: "#f0f0f0",
                                  marginBottom: "10px",
                                  display: "flex",
                                  alignItems: "center",
                                  justifyContent: "center",
                                },
                              },
                              __("Image placeholder", "mediapilote")
                            ),
                        item.subtitle &&
                          wp.element.createElement(
                            "h3",
                            {
                              style: { fontSize: "18px", marginBottom: "10px" },
                            },
                            item.subtitle
                          ),
                        item.description &&
                          wp.element.createElement(
                            "p",
                            {
                              style: { fontSize: "14px" },
                            },
                            item.description
                          )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      );
    },

    save: function () {
      return null; // Rendu côté serveur
    },
  });
})(window.wp);
