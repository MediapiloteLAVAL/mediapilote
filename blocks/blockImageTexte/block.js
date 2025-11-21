(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, PanelColorSettings, MediaUpload } = wp.blockEditor;
    const { PanelBody, TextControl, Button } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/image-texte', {
        title: __('Bloc Image Texte', 'mediapilote'),
        icon: 'format-image',
        category: 'design',
        keywords: [__('image', 'mediapilote'), __('texte', 'mediapilote'), __('section', 'mediapilote')],

        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { title, description, buttonText, buttonUrl, backgroundColor, imageUrl, imageId, reverse } = attributes;

            const onSelectImage = function(media) {
                setAttributes({
                    imageUrl: media.url,
                    imageId: media.id
                });
            };

            const onRemoveImage = function() {
                setAttributes({
                    imageUrl: '',
                    imageId: 0
                });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, {
                        title: __('Paramètres du bloc', 'mediapilote'),
                        initialOpen: true
                    },
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Image', 'mediapilote')
                            ),
                            wp.element.createElement(MediaUpload, {
                                onSelect: onSelectImage,
                                allowedTypes: ['image'],
                                value: imageId,
                                render: function(obj) {
                                    return wp.element.createElement('div', { style: { margin: '10px 0', background: '#f8f8f8', padding: '8px', borderRadius: '4px', textAlign: 'center' } },
                                        imageUrl ?
                                            wp.element.createElement('img', {
                                                src: imageUrl,
                                                alt: __('Image', 'mediapilote'),
                                                style: { maxWidth: '100%', maxHeight: '120px', display: 'block', margin: '0 auto' }
                                            }) :
                                            wp.element.createElement(Button, {
                                                className: 'button button-large',
                                                onClick: obj.open
                                            }, __('Choisir une image', 'mediapilote'))
                                    );
                                }
                            }),
                            imageUrl && wp.element.createElement(Button, {
                                onClick: onRemoveImage,
                                isDestructive: true,
                                style: { marginTop: '10px' }
                            }, __('Supprimer l\'image', 'mediapilote'))
                        ),
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
                        wp.element.createElement(PanelColorSettings, {
                            title: __('Couleur de fond', 'mediapilote'),
                            colorSettings: [{
                                value: backgroundColor,
                                onChange: function(color) { setAttributes({ backgroundColor: color }); },
                                label: __('Couleur', 'mediapilote')
                            }]
                        }),
                        wp.element.createElement(wp.components.ToggleControl, {
                            label: __('Inverser image et texte', 'mediapilote'),
                            checked: reverse,
                            onChange: function(value) { setAttributes({ reverse: value }); }
                        })
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-image-texte image-texte-section alignfull',
                    style: { backgroundColor: backgroundColor }
                },
                    wp.element.createElement('div', { className: 'container-fluid' },
                        wp.element.createElement('div', { className: 'image-texte-section__content row' },
                            reverse ? [
                                wp.element.createElement('div', { className: 'image-texte-section__image col-xl-5 col-lg-6 col-sm-12', style: imageUrl ? { backgroundImage: 'url(' + imageUrl + ')' } : {} }),
                                wp.element.createElement('div', { className: 'image-texte-section__text col-xl-7 col-lg-6 col-sm-12' },
                                    wp.element.createElement('h2', { className: 'image-texte-section__title' }, title),
                                    wp.element.createElement('div', { className: 'image-texte-section__description' },
                                        wp.element.createElement('p', null, description)
                                    ),
                                    wp.element.createElement('button', { className: 'btn' },
                                        wp.element.createElement('span', { className: 'btn-text' }, buttonText)
                                    )
                                )
                            ] : [
                                wp.element.createElement('div', { className: 'image-texte-section__text col-xl-7 col-lg-6 col-sm-12' },
                                    wp.element.createElement('h2', { className: 'image-texte-section__title' }, title),
                                    wp.element.createElement('div', { className: 'image-texte-section__description' },
                                        wp.element.createElement('p', null, description)
                                    ),
                                    wp.element.createElement('button', { className: 'btn' },
                                        wp.element.createElement('span', { className: 'btn-text' }, buttonText)
                                    )
                                ),
                                wp.element.createElement('div', { className: 'image-texte-section__image col-xl-5 col-lg-6 col-sm-12', style: imageUrl ? { backgroundImage: 'url(' + imageUrl + ')' } : {} })
                            ]
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