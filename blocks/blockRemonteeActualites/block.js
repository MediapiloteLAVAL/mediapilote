(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { RichText, InspectorControls } = wp.blockEditor;
  const { PanelBody, TextControl, TextareaControl, ColorPalette } =
    wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/remontee-actualites", {
    title: __("Remontée d'Actualités", "mediapilote"),
    icon: "admin-post",
    category: "design",
    keywords: [
      __("actualités", "mediapilote"),
      __("news", "mediapilote"),
      __("slider", "mediapilote"),
      __("blog", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const {
        title,
        description,
        backgroundColor,
        textColor,
        buttonText,
        buttonLink,
      } = attributes;

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
              help: __("Titre principal du bloc", "mediapilote"),
            }),
            wp.element.createElement(TextareaControl, {
              label: __("Description", "mediapilote"),
              value: description,
              onChange: (value) => setAttributes({ description: value }),
              help: __("Description du bloc", "mediapilote"),
            }),
            wp.element.createElement(TextControl, {
              label: __("Texte du bouton", "mediapilote"),
              value: buttonText,
              onChange: (value) => setAttributes({ buttonText: value }),
            }),
            wp.element.createElement(TextControl, {
              label: __("Lien du bouton", "mediapilote"),
              value: buttonLink,
              onChange: (value) => setAttributes({ buttonLink: value }),
              help: __("Par défaut: /blog", "mediapilote"),
            })
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
                onChange: (value) => setAttributes({ backgroundColor: value }),
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
                onChange: (value) => setAttributes({ textColor: value }),
              })
            )
          )
        ),
        wp.element.createElement(
          "div",
          {
            className:
              className + " wp-block-mediapilote-remontee-actualites alignfull",
            style: {
              backgroundColor: backgroundColor,
              color: textColor,
              padding: "60px 0",
            },
          },
          wp.element.createElement(
            "div",
            {
              className: "container",
              style: {
                maxWidth: "1200px",
                margin: "0 auto",
                padding: "0 20px",
              },
            },
            // Titre et description
            wp.element.createElement(
              "div",
              {
                className: "header-content",
                style: {
                  marginBottom: "60px",
                  display: "flex",
                  flexDirection: "column",
                  alignItems: "flex-start",
                },
              },
              wp.element.createElement(
                "h2",
                {
                  className: "actualites-title",
                  style: {
                    fontSize: "70px",
                    fontWeight: "normal",
                    lineHeight: "normal",
                    marginBottom: "30px",
                    color: textColor,
                    fontFamily: "'Inter', sans-serif",
                  },
                },
                title
              ),
              wp.element.createElement(
                "p",
                {
                  className: "actualites-description",
                  style: {
                    fontSize: "18px",
                    lineHeight: "28px",
                    marginBottom: "0",
                    color: textColor,
                    fontFamily: "'Inter', sans-serif",
                    fontWeight: "normal",
                    maxWidth: "800px",
                  },
                },
                description
              )
            ),

            // Container navigation + slider
            wp.element.createElement(
              "div",
              {
                className: "slider-container",
                style: {
                  display: "flex",
                  alignItems: "flex-start",
                  gap: "20px",
                  marginBottom: "60px",
                },
              },
              // Navigation arrows
              wp.element.createElement(
                "div",
                {
                  className: "slider-navigation",
                  style: {
                    display: "flex",
                    flexDirection: "row",
                    gap: "10px",
                    flexShrink: "0",
                  },
                },
                wp.element.createElement(
                  "div",
                  {
                    className: "slider-arrow slider-prev",
                    style: {
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      width: "52px",
                      height: "52px",
                      backgroundColor: textColor,
                    },
                  },
                  wp.element.createElement(
                    "svg",
                    {
                      width: "12",
                      height: "24",
                      viewBox: "0 0 12 24",
                      fill: "none",
                    },
                    wp.element.createElement("path", {
                      d: "M11 1L1 12L11 23",
                      stroke: backgroundColor,
                      strokeWidth: "2",
                      strokeLinecap: "round",
                      strokeLinejoin: "round",
                    })
                  )
                ),
                wp.element.createElement(
                  "div",
                  {
                    className: "slider-arrow slider-next",
                    style: {
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      width: "52px",
                      height: "52px",
                      backgroundColor: textColor,
                      transform: "rotate(180deg)",
                    },
                  },
                  wp.element.createElement(
                    "svg",
                    {
                      width: "12",
                      height: "24",
                      viewBox: "0 0 12 24",
                      fill: "none",
                    },
                    wp.element.createElement("path", {
                      d: "M11 1L1 12L11 23",
                      stroke: backgroundColor,
                      strokeWidth: "2",
                      strokeLinecap: "round",
                      strokeLinejoin: "round",
                    })
                  )
                )
              ),

              // Aperçu des actualités
              wp.element.createElement(
                "div",
                {
                  className: "actualites-preview",
                  style: { flex: "1", minWidth: "0" },
                },
                wp.element.createElement(
                  "div",
                  {
                    style: {
                      display: "grid",
                      gridTemplateColumns:
                        "repeat(auto-fit, minmax(300px, 1fr))",
                      gap: "30px",
                    },
                  },
                  // Simuler 3 articles en preview
                  Array.from({ length: 3 }, (_, index) =>
                    wp.element.createElement(
                      "div",
                      {
                        key: index,
                        className: "actualite-item-preview",
                        style: { maxWidth: "398px" },
                      },
                      wp.element.createElement(
                        "div",
                        {
                          style: {
                            width: "100%",
                            height: "299px",
                            backgroundColor: "#ddd",
                            marginBottom: "20px",
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                            color: "#999",
                          },
                        },
                        "Image"
                      ),
                      wp.element.createElement(
                        "h3",
                        {
                          style: {
                            fontSize: "28px",
                            lineHeight: "37px",
                            marginBottom: "15px",
                            color: textColor,
                            fontFamily: "'Inter', sans-serif",
                            fontWeight: "normal",
                          },
                        },
                        "Lorem ipsum"
                      ),
                      wp.element.createElement(
                        "div",
                        {
                          style: {
                            fontSize: "20px",
                            lineHeight: "28px",
                            color: textColor,
                            fontFamily: "'Inter', sans-serif",
                            fontWeight: "normal",
                          },
                        },
                        wp.element.createElement(
                          "p",
                          { style: { marginBottom: "0" } },
                          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna…"
                        ),
                        wp.element.createElement(
                          "p",
                          { style: { marginBottom: "0", marginTop: "0" } },
                          wp.element.createElement(
                            "span",
                            {
                              style: {
                                color: textColor,
                                textDecoration: "underline",
                              },
                            },
                            "Lire la suite"
                          )
                        )
                      )
                    )
                  )
                )
              )
            ),

            // Bouton Voir tout
            wp.element.createElement(
              "div",
              {
                className: "actualites-button",
                style: { textAlign: "center", marginTop: "60px" },
              },
              wp.element.createElement(
                "a",
                {
                  href: buttonLink,
                  className: "voir-tout-btn",
                  style: {
                    display: "inline-block",
                    padding: "0",
                    background: "transparent",
                    border: "2px solid " + textColor,
                    position: "relative",
                    textDecoration: "none",
                    width: "260px",
                    height: "52px",
                    overflow: "hidden",
                  },
                },
                wp.element.createElement("div", {
                  style: {
                    position: "absolute",
                    top: "0",
                    left: "0",
                    right: "0",
                    bottom: "2px",
                    zIndex: "1",
                  },
                }),
                wp.element.createElement(
                  "span",
                  {
                    style: {
                      position: "relative",
                      zIndex: "2",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      height: "100%",
                      fontSize: "20px",
                      color: textColor,
                      fontWeight: "normal",
                      fontFamily: "'Inter', sans-serif",
                      letterSpacing: "3px",
                      textTransform: "uppercase",
                      lineHeight: "60px",
                    },
                  },
                  buttonText
                )
              )
            ),

            // Note pour l'éditeur
            wp.element.createElement(
              "div",
              {
                style: {
                  marginTop: "30px",
                  padding: "15px",
                  background: "rgba(0,0,0,0.1)",
                  borderRadius: "4px",
                  fontSize: "14px",
                  fontStyle: "italic",
                },
              },
              __(
                "📝 Aperçu éditeur : Ce bloc affichera automatiquement les 10 dernières actualités dans un slider responsive sur le frontend.",
                "mediapilote"
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
