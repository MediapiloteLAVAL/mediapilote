(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const { PanelBody, Button, RangeControl } = wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/images", {
    title: __("Bloc Images", "mediapilote"),
    icon: "format-gallery",
    category: "media",
    keywords: [
      __("images", "mediapilote"),
      __("galerie", "mediapilote"),
      __("photos", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const { images, bannerHeight } = attributes;

      const onSelectImages = function (newImages) {
        // Limiter à 5 images maximum
        const limitedImages = newImages.slice(0, 5);
        setAttributes({
          images: limitedImages.map((img) => ({
            id: img.id,
            url: img.url,
            alt: img.alt || "",
          })),
        });
      };

      const removeImage = function (indexToRemove) {
        const updatedImages = images.filter(
          (image, index) => index !== indexToRemove
        );
        setAttributes({ images: updatedImages });
      };

      const moveImage = function (fromIndex, toIndex) {
        const updatedImages = [...images];
        const movedItem = updatedImages.splice(fromIndex, 1)[0];
        updatedImages.splice(toIndex, 0, movedItem);
        setAttributes({ images: updatedImages });
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
              title: __("Paramètres du bloc", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(RangeControl, {
              label: __("Hauteur du bandeau (px)", "mediapilote"),
              value: bannerHeight,
              onChange: function (value) {
                setAttributes({ bannerHeight: value });
              },
              min: 200,
              max: 800,
              step: 10,
            }),
            wp.element.createElement(
              "div",
              { style: { marginBottom: "20px" } },
              wp.element.createElement(
                "label",
                {
                  style: {
                    display: "block",
                    marginBottom: "10px",
                    fontWeight: "bold",
                  },
                },
                __("Images", "mediapilote") + " (" + images.length + "/5)"
              ),
              wp.element.createElement(
                MediaUploadCheck,
                null,
                wp.element.createElement(MediaUpload, {
                  onSelect: onSelectImages,
                  allowedTypes: ["image"],
                  multiple: true,
                  value: images.map((img) => img.id),
                  render: function (obj) {
                    return wp.element.createElement(
                      Button,
                      {
                        onClick: obj.open,
                        isPrimary: true,
                        disabled: images.length >= 5,
                      },
                      images.length >= 5
                        ? __("Limite de 5 images atteinte", "mediapilote")
                        : __("Ajouter des images", "mediapilote")
                    );
                  },
                })
              )
            ),
            images.length > 0 &&
              wp.element.createElement(
                "div",
                { style: { marginTop: "10px" } },
                images.map(function (image, index) {
                  return wp.element.createElement(
                    "div",
                    {
                      key: image.id,
                      style: {
                        display: "flex",
                        alignItems: "center",
                        marginBottom: "10px",
                        padding: "10px",
                        border: "1px solid #ddd",
                        borderRadius: "4px",
                      },
                    },
                    wp.element.createElement("img", {
                      src: image.url,
                      alt: image.alt,
                      style: {
                        width: "50px",
                        height: "50px",
                        objectFit: "cover",
                        marginRight: "10px",
                      },
                    }),
                    wp.element.createElement(
                      "span",
                      {
                        style: { flex: 1, fontSize: "12px" },
                      },
                      __("Image", "mediapilote") + " " + (index + 1)
                    ),
                    wp.element.createElement(
                      "div",
                      { style: { display: "flex", gap: "5px" } },
                      index > 0 &&
                        wp.element.createElement(
                          Button,
                          {
                            onClick: function () {
                              moveImage(index, index - 1);
                            },
                            isSmall: true,
                          },
                          "↑"
                        ),
                      index < images.length - 1 &&
                        wp.element.createElement(
                          Button,
                          {
                            onClick: function () {
                              moveImage(index, index + 1);
                            },
                            isSmall: true,
                          },
                          "↓"
                        ),
                      wp.element.createElement(
                        Button,
                        {
                          onClick: function () {
                            removeImage(index);
                          },
                          isDestructive: true,
                          isSmall: true,
                        },
                        "×"
                      )
                    )
                  );
                })
              )
          )
        ),
        wp.element.createElement(
          "div",
          {
            className:
              className + " wp-block-mediapilote-images block-images alignfull",
          },
          wp.element.createElement(
            "div",
            { className: "container-fluid no-padding" },
            wp.element.createElement(
              "div",
              {
                className: "block-images__wrapper",
              },
              images.length > 0
                ? wp.element.createElement(
                    "div",
                    {
                      className: "block-images__gallery",
                      style: { height: bannerHeight + "px" },
                    },
                    images.map(function (image, index) {
                      return wp.element.createElement(
                        "div",
                        {
                          key: image.id,
                          className: "block-images__item",
                        },
                        wp.element.createElement("img", {
                          src: image.url,
                          alt: image.alt,
                          className: "block-images__image",
                          style: { height: bannerHeight + "px" },
                        })
                      );
                    })
                  )
                : wp.element.createElement(
                    "div",
                    {
                      style: {
                        padding: "40px",
                        textAlign: "center",
                        border: "2px dashed #ddd",
                        borderRadius: "8px",
                        color: "#666",
                      },
                    },
                    wp.element.createElement(
                      "p",
                      null,
                      __("Aucune image sélectionnée.", "mediapilote")
                    ),
                    wp.element.createElement(
                      "p",
                      null,
                      __(
                        "Utilisez le panneau de droite pour ajouter des images.",
                        "mediapilote"
                      )
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
