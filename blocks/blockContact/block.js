(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, PanelColorSettings, MediaUpload } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/contact', {
        title: __('Bloc Contact', 'mediapilote'),
        icon: 'email',
        category: 'design',
        keywords: [__('contact', 'mediapilote'), __('formulaire', 'mediapilote'), __('email', 'mediapilote')],

        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { title, subtitle, description, backgroundColor, textColor, imageUrl, imageId } = attributes;

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
                                    return wp.element.createElement('div', { 
                                        style: { 
                                            margin: '10px 0', 
                                            background: '#f8f8f8', 
                                            padding: '8px', 
                                            borderRadius: '4px', 
                                            textAlign: 'center' 
                                        } 
                                    },
                                        imageUrl ?
                                            wp.element.createElement('img', {
                                                src: imageUrl,
                                                alt: __('Image', 'mediapilote'),
                                                style: { 
                                                    maxWidth: '100%', 
                                                    maxHeight: '120px', 
                                                    display: 'block', 
                                                    margin: '0 auto' 
                                                }
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
                            label: __('Titre principal', 'mediapilote'),
                            value: title,
                            onChange: function(value) { setAttributes({ title: value }); }
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Sous-titre', 'mediapilote'),
                            value: subtitle,
                            onChange: function(value) { setAttributes({ subtitle: value }); }
                        }),
                        wp.element.createElement(TextareaControl, {
                            label: __('Description', 'mediapilote'),
                            value: description,
                            onChange: function(value) { setAttributes({ description: value }); },
                            rows: 5
                        }),
                        wp.element.createElement(PanelColorSettings, {
                            title: __('Couleurs', 'mediapilote'),
                            colorSettings: [
                                {
                                    value: backgroundColor,
                                    onChange: function(color) { setAttributes({ backgroundColor: color }); },
                                    label: __('Couleur de fond', 'mediapilote')
                                },
                                {
                                    value: textColor,
                                    onChange: function(color) { setAttributes({ textColor: color }); },
                                    label: __('Couleur du texte', 'mediapilote')
                                }
                            ]
                        })
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-contact contact-section alignfull',
                    style: { 
                        backgroundColor: backgroundColor,
                        color: textColor
                    }
                },
                    wp.element.createElement('div', { className: 'container-fluid' },
                        wp.element.createElement('div', { className: 'contact-section__wrapper' },
                            // Colonne gauche
                            wp.element.createElement('div', { className: 'contact-section__left' },
                                title && wp.element.createElement('h1', { className: 'contact-section__title' }, title),
                                wp.element.createElement('div', { className: 'contact-section__content' },
                                    subtitle && wp.element.createElement('h3', { 
                                        className: 'contact-section__subtitle',
                                        style: { color: '#E0E648' }
                                    }, subtitle),
                                    description && wp.element.createElement('div', { className: 'contact-section__description' },
                                        wp.element.createElement('p', null, description)
                                    )
                                ),
                                imageUrl && wp.element.createElement('div', { className: 'contact-section__image' },
                                    wp.element.createElement('img', {
                                        src: imageUrl,
                                        alt: title,
                                        style: { maxWidth: '100%', height: 'auto' }
                                    })
                                )
                            ),
                            // Colonne droite - Formulaire (aperçu)
                            wp.element.createElement('div', { className: 'contact-section__right' },
                                wp.element.createElement('form', { className: 'contact-form' },
                                    wp.element.createElement('div', { className: 'contact-form__field' },
                                        wp.element.createElement('input', {
                                            type: 'text',
                                            className: 'contact-form__input',
                                            placeholder: 'Votre nom',
                                            disabled: true
                                        }),
                                        wp.element.createElement('span', { className: 'contact-form__line' })
                                    ),
                                    wp.element.createElement('div', { className: 'contact-form__field' },
                                        wp.element.createElement('input', {
                                            type: 'email',
                                            className: 'contact-form__input',
                                            placeholder: 'Votre email',
                                            disabled: true
                                        }),
                                        wp.element.createElement('span', { className: 'contact-form__line' })
                                    ),
                                    wp.element.createElement('div', { className: 'contact-form__field contact-form__field--large' },
                                        wp.element.createElement('textarea', {
                                            className: 'contact-form__textarea',
                                            placeholder: 'Votre message',
                                            rows: 5,
                                            disabled: true
                                        }),
                                        wp.element.createElement('span', { className: 'contact-form__line' })
                                    ),
                                    wp.element.createElement('div', { className: 'contact-form__submit' },
                                        wp.element.createElement('button', { 
                                            className: 'btn btn--contact',
                                            type: 'button',
                                            disabled: true,
                                            style: { 
                                                border: '2px solid ' + textColor,
                                                color: textColor
                                            }
                                        },
                                            wp.element.createElement('span', { className: 'btn-text' }, 'Nous contacter'),
                                            wp.element.createElement('svg', {
                                                className: 'btn-arrow',
                                                width: '260',
                                                height: '52',
                                                viewBox: '0 0 260 52',
                                                fill: 'none',
                                                xmlns: 'http://www.w3.org/2000/svg'
                                            },
                                                wp.element.createElement('path', {
                                                    d: 'M229.293 25.2929C228.902 25.6834 228.902 26.3166 229.293 26.7071L235.657 33.0711C236.047 33.4616 236.681 33.4616 237.071 33.0711C237.462 32.6805 237.462 32.0474 237.071 31.6569L231.414 26L237.071 20.3431C237.462 19.9526 237.462 19.3195 237.071 18.9289C236.681 18.5384 236.047 18.5384 235.657 18.9289L229.293 25.2929ZM260 25H230V27H260V25Z',
                                                    fill: 'currentColor'
                                                }),
                                                wp.element.createElement('line', {
                                                    x1: '0',
                                                    y1: '51',
                                                    x2: '260',
                                                    y2: '51',
                                                    stroke: 'currentColor',
                                                    strokeWidth: '2'
                                                })
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

        save: function() {
            // Rendu côté serveur via PHP
            return null;
        }
    });
})(window.wp);
