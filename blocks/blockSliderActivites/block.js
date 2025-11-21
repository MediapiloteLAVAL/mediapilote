(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { RichText, InspectorControls } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, ColorPalette, RangeControl } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/slider-activites', {
        title: __('Slider d\'Activités', 'mediapilote'),
        icon: 'list-view',
        category: 'design',
        keywords: [__('activités', 'mediapilote'), __('slider', 'mediapilote'), __('posts', 'mediapilote')],

        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { title, description, backgroundColor, textColor, buttonText, buttonLink, postsPerPage } = attributes;

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, {
                        title: __('Contenu', 'mediapilote'),
                        initialOpen: true
                    },
                        wp.element.createElement(TextControl, {
                            label: __('Titre', 'mediapilote'),
                            value: title,
                            onChange: (value) => setAttributes({ title: value })
                        }),
                        wp.element.createElement(TextareaControl, {
                            label: __('Description', 'mediapilote'),
                            value: description,
                            onChange: (value) => setAttributes({ description: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Texte du bouton', 'mediapilote'),
                            value: buttonText,
                            onChange: (value) => setAttributes({ buttonText: value })
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Lien du bouton', 'mediapilote'),
                            value: buttonLink,
                            onChange: (value) => setAttributes({ buttonLink: value })
                        }),
                        wp.element.createElement(RangeControl, {
                            label: __('Nombre de posts', 'mediapilote'),
                            value: postsPerPage,
                            onChange: (value) => setAttributes({ postsPerPage: value }),
                            min: 3,
                            max: 12
                        })
                    ),
                    wp.element.createElement(PanelBody, {
                        title: __('Style', 'mediapilote'),
                        initialOpen: false
                    },
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Couleur d\'arrière-plan', 'mediapilote')
                            ),
                            wp.element.createElement(ColorPalette, {
                                value: backgroundColor,
                                onChange: (value) => setAttributes({ backgroundColor: value })
                            })
                        ),
                        wp.element.createElement('div', { className: 'components-base-control', style: { marginTop: '20px' } },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Couleur du texte', 'mediapilote')
                            ),
                            wp.element.createElement(ColorPalette, {
                                value: textColor,
                                onChange: (value) => setAttributes({ textColor: value })
                            })
                        )
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-slider-activites alignfull',
                    style: { backgroundColor: backgroundColor, color: textColor, padding: '60px 0' }
                },
                    wp.element.createElement('div', { className: 'container' },
                        wp.element.createElement('h2', {
                            style: { fontSize: '70px', marginBottom: '20px', color: textColor }
                        }, title),
                        wp.element.createElement('p', {
                            style: { fontSize: '18px', lineHeight: '28px', marginBottom: '40px', color: textColor }
                        }, description),
                        wp.element.createElement('div', { className: 'activites-preview' },
                            wp.element.createElement('p', null, __('Aperçu du slider d\'activités - Les posts seront affichés ici dans l\'éditeur.', 'mediapilote'))
                        ),
                        wp.element.createElement('div', {
                            className: 'activites-button',
                            style: { textAlign: 'center', marginTop: '40px' }
                        },
                            wp.element.createElement('a', {
                                href: buttonLink,
                                style: {
                                    display: 'inline-block',
                                    padding: '15px 30px',
                                    backgroundColor: textColor,
                                    color: backgroundColor,
                                    textDecoration: 'none',
                                    fontSize: '20px',
                                    fontWeight: 'bold',
                                    textTransform: 'uppercase',
                                    letterSpacing: '3px'
                                }
                            }, buttonText)
                        )
                    )
                )
            );
        },

        save: function() {
            return null; // Rendu côté serveur
        }
    });
})(window.wp);