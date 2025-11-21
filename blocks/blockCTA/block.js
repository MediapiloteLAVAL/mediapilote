(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, TextControl, ColorPalette, RangeControl } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/cta', {
        title: __('Bloc CTA', 'mediapilote'),
        icon: 'megaphone',
        category: 'design',
        keywords: [__('cta', 'mediapilote'), __('appel à action', 'mediapilote'), __('bouton', 'mediapilote')],

        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { title, description, buttonText, buttonUrl, backgroundColor, textColor, padding } = attributes;

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
                            onChange: function(value) { setAttributes({ title: value }); }
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Description', 'mediapilote'),
                            value: description,
                            onChange: function(value) { setAttributes({ description: value }); }
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Texte du bouton', 'mediapilote'),
                            value: buttonText,
                            onChange: function(value) { setAttributes({ buttonText: value }); }
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('URL du bouton', 'mediapilote'),
                            value: buttonUrl,
                            onChange: function(value) { setAttributes({ buttonUrl: value }); }
                        })
                    ),
                    wp.element.createElement(PanelBody, {
                        title: __('Style', 'mediapilote'),
                        initialOpen: false
                    },
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' }, __('Couleur de fond', 'mediapilote')),
                            wp.element.createElement(ColorPalette, {
                                value: backgroundColor,
                                onChange: function(color) { setAttributes({ backgroundColor: color }); }
                            })
                        ),
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' }, __('Couleur du texte', 'mediapilote')),
                            wp.element.createElement(ColorPalette, {
                                value: textColor,
                                onChange: function(color) { setAttributes({ textColor: color }); }
                            })
                        ),
                        wp.element.createElement(RangeControl, {
                            label: __('Marge interne (px)', 'mediapilote'),
                            value: padding,
                            onChange: function(value) { setAttributes({ padding: value }); },
                            min: 0,
                            max: 200,
                            step: 10
                        })
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-cta cta-section alignfull',
                    style: { backgroundColor: backgroundColor, padding: `${padding}px 0` }
                },
                    wp.element.createElement('div', { className: 'container-fluid' },
                        wp.element.createElement('div', { className: 'cta-section__content', style: { color: textColor } },
                            wp.element.createElement('h2', { className: 'cta-section__title' }, title),
                            wp.element.createElement('div', { className: 'cta-section__description' },
                                wp.element.createElement('p', null, description)
                            ),
                            wp.element.createElement('a', { href: buttonUrl, className: 'btn' },
                                wp.element.createElement('span', { className: 'btn-text' }, buttonText)
                            )
                        )
                    )
                )
            );
        },

        save: function() {
            // Rendu côté serveur via PHP
            return null;
        }
    });
})(window.wp);