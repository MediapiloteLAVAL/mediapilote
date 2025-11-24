(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const {
    PanelBody,
    TextControl,
    TextareaControl,
    ColorPalette,
    SelectControl,
    Button,
    RangeControl,
    ToggleControl,
  } = wp.components;
  const { __ } = wp.i18n;
  const { Fragment, useState } = wp.element;

  registerBlockType("mediapilote/chiffres-cles", {
    title: __("Bloc Chiffres Clés", "mediapilote"),
    icon: "chart-bar",
    category: "design",
    keywords: [
      __("chiffres", "mediapilote"),
      __("statistiques", "mediapilote"),
      __("points clés", "mediapilote"),
      __("kpi", "mediapilote"),
    ],

    edit: function (props) {
      const { attributes, setAttributes, className } = props;
      const { backgroundColor, textColor, title, description, mode, items } =
        attributes;

      // Couleurs prédéfinies
      const colors = [
        { name: "Blanc", color: "#ffffff" },
        { name: "Noir", color: "#000000" },
        { name: "Vert principal", color: "#0a3c33" },
        { name: "Gris foncé", color: "#2d3037" },
        { name: "Gris clair", color: "#f8f9fa" },
      ];

      // Fonction pour ajouter un élément
      const addItem = () => {
        if (items.length < 5) {
          const newItems = [...items];
          newItems.push({
            value: mode === "chiffres" ? "0" : "",
            label: "",
            icon: null,
          });
          setAttributes({ items: newItems });
        }
      };

      // Fonction pour supprimer un élément
      const removeItem = (index) => {
        const newItems = items.filter((_, i) => i !== index);
        setAttributes({ items: newItems });
      };

      // Fonction pour mettre à jour un élément
      const updateItem = (index, key, value) => {
        const newItems = [...items];
        newItems[index] = { ...newItems[index], [key]: value };
        setAttributes({ items: newItems });
      };

      // Calculer les classes de colonnes
      const getColClass = (nbItems) => {
        switch (nbItems) {
          case 1:
            return "col-12";
          case 2:
            return "col-md-6";
          case 3:
            return "col-lg-4";
          case 4:
            return "col-lg-3 col-md-6";
          case 5:
            return "col-xl-2 col-lg-4 col-md-6";
          default:
            return "col-lg-4";
        }
      };

      const colClass = getColClass(items.length);

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(
          InspectorControls,
          null,
          // Panel Apparence
          wp.element.createElement(
            PanelBody,
            {
              title: __("Apparence", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(
              "p",
              { style: { marginBottom: "10px" } },
              __("Couleur de fond", "mediapilote")
            ),
            wp.element.createElement(ColorPalette, {
              colors: colors,
              value: backgroundColor,
              onChange: (value) =>
                setAttributes({ backgroundColor: value || "#ffffff" }),
            }),
            wp.element.createElement(
              "p",
              { style: { marginBottom: "10px" } },
              __("Couleur du texte", "mediapilote")
            ),
            wp.element.createElement(ColorPalette, {
              colors: colors,
              value: textColor,
              onChange: (value) =>
                setAttributes({ textColor: value || "#2d3037" }),
            })
          ),

          // Panel Contenu
          wp.element.createElement(
            PanelBody,
            {
              title: __("Contenu", "mediapilote"),
              initialOpen: true,
            },
            wp.element.createElement(TextControl, {
              label: __("Titre (H2)", "mediapilote"),
              value: title,
              onChange: (value) => setAttributes({ title: value }),
              help: __("Titre principal du bloc (optionnel)", "mediapilote"),
            }),
            wp.element.createElement(TextareaControl, {
              label: __("Description", "mediapilote"),
              value: description,
              onChange: (value) => setAttributes({ description: value }),
              help: __(
                "Description affichée sous le titre (optionnel)",
                "mediapilote"
              ),
              rows: 3,
            })
          ),

          // Panel Configuration
          wp.element.createElement(
            PanelBody,
            {
              title: __("Configuration", "mediapilote"),
              initialOpen: false,
            },
            wp.element.createElement(SelectControl, {
              label: __("Mode d'affichage", "mediapilote"),
              value: mode,
              options: [
                {
                  label: __("Chiffres clés", "mediapilote"),
                  value: "chiffres",
                },
                {
                  label: __("Points clés (icônes)", "mediapilote"),
                  value: "points",
                },
              ],
              onChange: (value) => setAttributes({ mode: value }),
            })
          ),

          // Panel Éléments
          wp.element.createElement(
            PanelBody,
            {
              title: __("Éléments", "mediapilote"),
              initialOpen: false,
            },
            items.map((item, index) =>
              wp.element.createElement(
                "div",
                {
                  key: index,
                  style: {
                    marginBottom: "20px",
                    padding: "15px",
                    border: "1px solid #ddd",
                    borderRadius: "4px",
                  },
                },
                wp.element.createElement(
                  "h4",
                  { style: { marginTop: 0 } },
                  __("Élément", "mediapilote") + " " + (index + 1)
                ),

                // Champ valeur/chiffre ou sélection d'icône
                mode === "chiffres"
                  ? wp.element.createElement(TextControl, {
                      label: __("Chiffre", "mediapilote"),
                      value: item.value || "",
                      onChange: (value) => updateItem(index, "value", value),
                      help: __(
                        "Le chiffre à afficher (ex: 150, 20K, 59%)",
                        "mediapilote"
                      ),
                    })
                  : wp.element.createElement(
                      "div",
                      null,
                      wp.element.createElement(
                        "p",
                        null,
                        wp.element.createElement(
                          "strong",
                          null,
                          __("Icône", "mediapilote")
                        )
                      ),
                      wp.element.createElement(
                        MediaUploadCheck,
                        null,
                        wp.element.createElement(MediaUpload, {
                          onSelect: (media) =>
                            updateItem(index, "icon", {
                              id: media.id,
                              url: media.url,
                              alt: media.alt,
                            }),
                          allowedTypes: ["image"],
                          value: item.icon ? item.icon.id : null,
                          render: ({ open }) =>
                            wp.element.createElement(
                              Button,
                              {
                                onClick: open,
                                variant: item.icon ? "secondary" : "primary",
                              },
                              item.icon
                                ? __("Changer l'icône", "mediapilote")
                                : __("Sélectionner une icône", "mediapilote")
                            ),
                        })
                      ),
                      item.icon &&
                        wp.element.createElement(
                          "div",
                          { style: { marginTop: "10px" } },
                          wp.element.createElement("img", {
                            src: item.icon.url,
                            alt: item.icon.alt,
                            style: { maxWidth: "50px", height: "auto" },
                          }),
                          wp.element.createElement(
                            Button,
                            {
                              onClick: () => updateItem(index, "icon", null),
                              variant: "link",
                              isDestructive: true,
                              style: { marginLeft: "10px" },
                            },
                            __("Supprimer", "mediapilote")
                          )
                        )
                    ),

                wp.element.createElement(TextControl, {
                  label: __("Libellé", "mediapilote"),
                  value: item.label || "",
                  onChange: (value) => updateItem(index, "label", value),
                  help: __(
                    "Le texte descriptif affiché sous le chiffre/icône",
                    "mediapilote"
                  ),
                }),

                items.length > 1 &&
                  wp.element.createElement(
                    Button,
                    {
                      onClick: () => removeItem(index),
                      variant: "secondary",
                      isDestructive: true,
                      style: { marginTop: "10px" },
                    },
                    __("Supprimer cet élément", "mediapilote")
                  )
              )
            ),
            items.length < 5 &&
              wp.element.createElement(
                Button,
                {
                  onClick: addItem,
                  variant: "primary",
                  style: { marginTop: "15px" },
                },
                __("Ajouter un élément", "mediapilote")
              ),
            items.length >= 5 &&
              wp.element.createElement(
                "p",
                { style: { fontStyle: "italic", color: "#666" } },
                __("Maximum 5 éléments autorisés", "mediapilote")
              )
          )
        ),

        // Rendu de l'éditeur
        wp.element.createElement(
          "section",
          {
            className: "bloc-chiffres-cles alignfull",
            style: {
              backgroundColor: backgroundColor,
              color: textColor,
              padding: "60px 20px",
              minHeight: "200px",
            },
          },
          wp.element.createElement(
            "div",
            { className: "container" },

            // Message d'information si pas de contenu
            !title &&
              !description &&
              wp.element.createElement(
                "div",
                { className: "row" },
                wp.element.createElement(
                  "div",
                  { className: "col-12 text-center mb-4" },
                  wp.element.createElement(
                    "div",
                    { className: "bloc-chiffres-cles__info" },
                    __(
                      "Ajoutez un titre ou une description dans le panneau de droite",
                      "mediapilote"
                    )
                  )
                )
              ),

            // Titre
            title &&
              wp.element.createElement(
                "div",
                { className: "row" },
                wp.element.createElement(
                  "div",
                  { className: "col-12 text-center mb-4" },
                  wp.element.createElement(
                    "h2",
                    {
                      className: "bloc-chiffres-cles__title",
                      style: {
                        color: textColor,
                        fontSize: "2rem",
                        marginBottom: "1rem",
                      },
                    },
                    title
                  )
                )
              ),

            // Description
            description &&
              wp.element.createElement(
                "div",
                { className: "row" },
                wp.element.createElement(
                  "div",
                  { className: "col-12 text-center mb-5" },
                  wp.element.createElement(
                    "p",
                    {
                      className: "bloc-chiffres-cles__description",
                      style: { color: textColor, fontSize: "1.1rem" },
                    },
                    description
                  )
                )
              ),

            // Éléments
            wp.element.createElement(
              "div",
              { className: "row justify-content-center" },
              items.length > 0
                ? items.map((item, index) =>
                    wp.element.createElement(
                      "div",
                      {
                        key: index,
                        className: colClass + " mb-4",
                      },
                      wp.element.createElement(
                        "div",
                        { className: "bloc-chiffres-cles__item text-center" },
                        mode === "chiffres"
                          ? wp.element.createElement(
                              "div",
                              {
                                className: "bloc-chiffres-cles__number",
                                style: {
                                  fontSize:
                                    items.length === 1 || items.length === 2
                                      ? "150px"
                                      : items.length === 3
                                      ? "130px"
                                      : items.length === 4
                                      ? "110px"
                                      : "90px",
                                  fontWeight: "normal",
                                  lineHeight: "1",
                                  color: textColor,
                                  marginBottom: "20px",
                                },
                              },
                              item.value || "0"
                            )
                          : item.icon &&
                              wp.element.createElement(
                                "div",
                                {
                                  className: "bloc-chiffres-cles__icon",
                                  style: { marginBottom: "20px" },
                                },
                                wp.element.createElement("img", {
                                  src: item.icon.url,
                                  alt: item.icon.alt,
                                  style: { maxWidth: "80px", height: "auto" },
                                })
                              ),

                        item.label &&
                          wp.element.createElement(
                            "p",
                            {
                              className: "bloc-chiffres-cles__label",
                              style: {
                                color: textColor,
                                fontSize: "20px",
                                lineHeight: "1.9",
                              },
                            },
                            item.label
                          )
                      )
                    )
                  )
                : wp.element.createElement(
                    "div",
                    { className: "col-12 text-center" },
                    wp.element.createElement(
                      "p",
                      { style: { fontStyle: "italic", opacity: 0.7 } },
                      __(
                        "Ajoutez des éléments dans les paramètres du bloc",
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
      // Le rendu est géré côté serveur
      return null;
    },
  });
})(window.wp);
