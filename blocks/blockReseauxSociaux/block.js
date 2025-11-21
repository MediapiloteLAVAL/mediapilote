(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls } = wp.blockEditor;
  const { PanelBody, RangeControl, ColorPalette } = wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/reseaux-sociaux", {
    title: __("Bloc Réseaux Sociaux", "mediapilote"),
    icon: "share",
    category: "design",
    keywords: [
      __("réseaux sociaux", "mediapilote"),
      __("social", "mediapilote"),
      __("liens", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const { backgroundColor, height } = attributes;

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
            wp.element.createElement(RangeControl, {
              label: __("Hauteur du bloc (px)", "mediapilote"),
              value: height,
              onChange: function (value) {
                setAttributes({ height: value });
              },
              min: 100,
              max: 500,
              step: 10,
            })
          )
        ),
        wp.element.createElement(
          "div",
          {
            className:
              className +
              " wp-block-mediapilote-reseaux-sociaux reseaux-sociaux-section alignfull",
            style: {
              backgroundColor: backgroundColor,
              height: `${height}px`,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              position: "relative",
            },
          },
          wp.element.createElement(
            "div",
            {
              className: "reseaux-sociaux-preview",
              style: {
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
              },
            },
            // Items avec bordures
            wp.element.createElement(
              "div",
              {
                style: {
                  display: "flex",
                  alignItems: "center",
                },
              },
              // Item LinkedIn
              wp.element.createElement(
                "div",
                {
                  style: {
                    width: "80px",
                    height: "60px",
                    border: "1px solid rgba(255, 255, 255, 0.8)",
                    borderRight: "none",
                    borderTopLeftRadius: "4px",
                    borderBottomLeftRadius: "4px",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    backgroundColor: "transparent",
                  },
                },
                wp.element.createElement(
                  "div",
                  {
                    style: {
                      width: "32px",
                      height: "32px",
                      backgroundColor: "#ffffff",
                      borderRadius: "4px",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      fontSize: "14px",
                      fontWeight: "bold",
                      color: backgroundColor || "#2d3037",
                    },
                  },
                  "in"
                )
              ),

              // Item YouTube
              wp.element.createElement(
                "div",
                {
                  style: {
                    width: "80px",
                    height: "60px",
                    border: "1px solid rgba(255, 255, 255, 0.8)",
                    borderTopRightRadius: "4px",
                    borderBottomRightRadius: "4px",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    backgroundColor: "transparent",
                  },
                },
                wp.element.createElement(
                  "div",
                  {
                    style: {
                      width: "32px",
                      height: "32px",
                      backgroundColor: "#ffffff",
                      borderRadius: "4px",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      fontSize: "12px",
                      fontWeight: "bold",
                      color: backgroundColor || "#2d3037",
                    },
                  },
                  "▶"
                )
              )
            ),

            // Message informatif
            wp.element.createElement(
              "div",
              {
                style: {
                  position: "absolute",
                  bottom: "-40px",
                  left: "50%",
                  transform: "translateX(-50%)",
                  color: "#666",
                  fontSize: "12px",
                  whiteSpace: "nowrap",
                },
              },
              __(
                "Les réseaux sociaux proviennent des champs ACF configurés dans les options",
                "mediapilote"
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
