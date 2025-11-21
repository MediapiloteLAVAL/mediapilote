(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, PanelColorSettings } = wp.blockEditor;
  const { PanelBody, TextControl, RangeControl, TextareaControl } =
    wp.components;
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;

  registerBlockType("mediapilote/texte-colonnes", {
    title: __("Bloc Texte en Colonnes", "mediapilote"),
    icon: "columns",
    category: "design",
    keywords: [
      __("texte", "mediapilote"),
      __("colonnes", "mediapilote"),
      __("text", "mediapilote"),
      __("columns", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const {
        title,
        titleColor,
        textColor,
        backgroundColor,
        columns,
        columnContent,
      } = attributes;

      // Fonction pour mettre à jour le contenu d'une colonne
      const updateColumnContent = function (index, value) {
        const newContent = [...columnContent];
        newContent[index] = value;
        setAttributes({ columnContent: newContent });
      };

      // Assurer que nous avons le bon nombre de colonnes de contenu
      const ensureColumnContent = function () {
        const currentContent = [...columnContent];
        while (currentContent.length < columns) {
          currentContent.push("");
        }
        if (currentContent.length !== columnContent.length) {
          setAttributes({ columnContent: currentContent });
        }
      };

      // S'assurer que nous avons le bon nombre de colonnes
      ensureColumnContent();

      // Calculer la classe CSS pour le nombre de colonnes
      const columnsClass = "mp-texte-columns-" + columns;

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
            wp.element.createElement(TextControl, {
              label: __("Titre", "mediapilote"),
              value: title,
              onChange: function (value) {
                setAttributes({ title: value });
              },
            }),
            wp.element.createElement(RangeControl, {
              label: __("Nombre de colonnes", "mediapilote"),
              value: columns,
              onChange: function (value) {
                setAttributes({ columns: value });
              },
              min: 1,
              max: 3,
              step: 1,
            })
          ),
          wp.element.createElement(PanelColorSettings, {
            title: __("Couleurs", "mediapilote"),
            initialOpen: false,
            colorSettings: [
              {
                value: titleColor,
                onChange: function (value) {
                  setAttributes({ titleColor: value || "#e0e648" });
                },
                label: __("Couleur du titre", "mediapilote"),
              },
              {
                value: textColor,
                onChange: function (value) {
                  setAttributes({ textColor: value || "#0a3c33" });
                },
                label: __("Couleur du texte", "mediapilote"),
              },
              {
                value: backgroundColor,
                onChange: function (value) {
                  setAttributes({ backgroundColor: value || "#f8f8f8" });
                },
                label: __("Couleur de fond", "mediapilote"),
              },
            ],
          })
        ),
        wp.element.createElement(
          "div",
          {
            className: "mp-texte-colonnes " + columnsClass + " alignfull",
            style: {
              backgroundColor: backgroundColor,
              padding: "60px 0",
              minHeight: "200px",
            },
          },
          wp.element.createElement(
            "div",
            {
              className: "mp-texte-colonnes-container",
              style: {
                maxWidth: "1200px",
                margin: "0 auto",
                padding: "0 20px",
              },
            },
            // Titre
            wp.element.createElement(
              "div",
              {
                style: { marginBottom: "40px" },
              },
              wp.element.createElement(TextControl, {
                placeholder: __("Tapez votre titre ici...", "mediapilote"),
                value: title,
                onChange: function (value) {
                  setAttributes({ title: value });
                },
                style: {
                  fontSize: "28px",
                  fontWeight: "normal",
                  color: titleColor,
                  backgroundColor: "transparent",
                  border: "none",
                  boxShadow: "none",
                  padding: "0",
                  margin: "0",
                  fontFamily: "Inter, sans-serif",
                },
              })
            ),
            // Conteneur des colonnes
            wp.element.createElement(
              "div",
              {
                className: "mp-texte-colonnes-content",
                style: {
                  display: "grid",
                  gridTemplateColumns:
                    columns === 1
                      ? "1fr"
                      : columns === 2
                      ? "1fr 1fr"
                      : "1fr 1fr 1fr",
                  gap: columns === 1 ? "0" : "60px",
                  alignItems: "start",
                },
              },
              // Générer les colonnes
              Array.from({ length: columns }, function (_, index) {
                return wp.element.createElement(
                  "div",
                  {
                    key: index,
                    className: "mp-texte-colonne",
                  },
                  wp.element.createElement("textarea", {
                    placeholder: __("Tapez votre texte ici...", "mediapilote"),
                    value: columnContent[index] || "",
                    onChange: function (e) {
                      const value = e.target.value;
                      updateColumnContent(index, value);
                      // Auto-resize
                      e.target.style.height = "auto";
                      e.target.style.height = e.target.scrollHeight + "px";
                    },
                    onInput: function (e) {
                      // Auto-resize on input
                      e.target.style.height = "auto";
                      e.target.style.height = e.target.scrollHeight + "px";
                    },
                    ref: function (textarea) {
                      if (textarea) {
                        // Initial sizing
                        setTimeout(function () {
                          textarea.style.height = "auto";
                          textarea.style.height = textarea.scrollHeight + "px";
                        }, 0);
                      }
                    },
                    style: {
                      fontSize: "20px",
                      lineHeight: "28px",
                      color: textColor,
                      backgroundColor: "transparent",
                      border: "1px dashed #ccc",
                      padding: "10px",
                      minHeight: "84px",
                      width: "100%",
                      fontFamily: "Inter, sans-serif",
                      fontWeight: "normal",
                      resize: "none",
                      overflow: "hidden",
                      boxSizing: "border-box",
                    },
                  })
                );
              })
            )
          )
        )
      );
    },

    save: function () {
      // Le rendu est géré côté serveur
      return null;
    },
  });
})(window.wp);
