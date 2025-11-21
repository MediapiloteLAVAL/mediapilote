(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { RichText, MediaUpload, InspectorControls, URLInput } = wp.blockEditor;
    const { PanelBody, Button, TextControl } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/entete', {
        title: __('Entête Hero', 'mediapilote'),
        icon: 'cover-image',
        category: 'design',
        keywords: [__('hero', 'mediapilote'), __('header', 'mediapilote'), __('banner', 'mediapilote')],
        
        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { content, images } = attributes;

            const updateContent = function(key, value) {
                const newContent = { ...content };
                newContent[key] = value;
                setAttributes({ content: newContent });
            };

            const updateImage = function(index, key, value) {
                const newImages = [...images];
                newImages[index][key] = value;
                setAttributes({ images: newImages });
            };

            const addImage = function() {
                const newImages = [...images, {
                    backgroundImageId: 0,
                    backgroundImageUrl: ''
                }];
                setAttributes({ images: newImages });
            };

            const removeImage = function(index) {
                if (images.length > 1) {
                    const newImages = images.filter((_, i) => i !== index);
                    setAttributes({ images: newImages });
                }
            };

            const onSelectImage = function(index, media) {
                updateImage(index, 'backgroundImageUrl', media.url);
                updateImage(index, 'backgroundImageId', media.id);
            };

            const onRemoveImage = function(index) {
                updateImage(index, 'backgroundImageUrl', '');
                updateImage(index, 'backgroundImageId', 0);
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, {
                        title: __('Contenu commun', 'mediapilote'),
                        initialOpen: true
                    },
                        wp.element.createElement(TextControl, {
                            label: __('Titre H1', 'mediapilote'),
                            value: content.title,
                            onChange: (value) => updateContent('title', value)
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Description', 'mediapilote'),
                            value: content.description,
                            onChange: (value) => updateContent('description', value)
                        }),
                        wp.element.createElement(TextControl, {
                            label: __('Texte du bouton', 'mediapilote'),
                            value: content.buttonText,
                            onChange: (value) => updateContent('buttonText', value)
                        }),
                        wp.element.createElement('div', { className: 'components-base-control', style: { marginBottom: '20px' } },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Lien du bouton', 'mediapilote')
                            ),
                            wp.element.createElement(URLInput, {
                                value: content.buttonUrl,
                                onChange: (value) => updateContent('buttonUrl', value)
                            })
                        )
                    ),
                    images.map((image, index) => wp.element.createElement(PanelBody, {
                        title: __('Image ' + (index + 1), 'mediapilote'),
                        initialOpen: index === 0
                    },
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Image de fond', 'mediapilote')
                            ),
                            wp.element.createElement(MediaUpload, {
                                onSelect: (media) => onSelectImage(index, media),
                                allowedTypes: ['image'],
                                value: image.backgroundImageId,
                                render: function(obj) {
                                    return wp.element.createElement('div', { style: { margin: '10px 0', background: '#f8f8f8', padding: '8px', borderRadius: '4px', textAlign: 'center' } },
                                        image.backgroundImageUrl ?
                                            wp.element.createElement('img', {
                                                src: image.backgroundImageUrl,
                                                alt: __('Image de fond', 'mediapilote'),
                                                style: { maxWidth: '100%', maxHeight: '120px', display: 'block', margin: '0 auto' }
                                            }) :
                                            wp.element.createElement(Button, {
                                                className: 'button button-large',
                                                onClick: obj.open
                                            }, __('Choisir une image', 'mediapilote'))
                                    );
                                }
                            }),
                            image.backgroundImageUrl && wp.element.createElement(Button, {
                                onClick: () => onRemoveImage(index),
                                isDestructive: true,
                                style: { marginTop: '10px' }
                            }, __('Supprimer l\'image', 'mediapilote'))
                        ),
                        images.length > 1 && wp.element.createElement(Button, {
                            onClick: () => removeImage(index),
                            isDestructive: true,
                            style: { marginTop: '10px' }
                        }, __('Supprimer cette image', 'mediapilote'))
                    )),
                    wp.element.createElement(PanelBody, {
                        title: __('Actions', 'mediapilote')
                    },
                        wp.element.createElement(Button, {
                            onClick: addImage,
                            isPrimary: true
                        }, __('Ajouter une image', 'mediapilote'))
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-entete hero-banner'
                },
                    wp.element.createElement('div', { className: 'hero-banner__slider' },
                        wp.element.createElement('div', {
                            className: 'hero-banner__container',
                            style: { backgroundImage: images[0] && images[0].backgroundImageUrl ? 'url(' + images[0].backgroundImageUrl + ')' : 'none' }
                        },
                            wp.element.createElement('div', { className: 'hero-banner__overlay' }),
                            wp.element.createElement('div', { className: 'hero-banner__content' },
                                wp.element.createElement('div', { className: 'hero-banner__text-content' },
                                    wp.element.createElement(RichText, {
                                        tagName: 'h1',
                                        className: 'hero-banner__title',
                                        value: content.title,
                                        onChange: (value) => updateContent('title', value),
                                        placeholder: __('Entrez le titre...', 'mediapilote')
                                    }),
                                    wp.element.createElement(RichText, {
                                        tagName: 'p',
                                        className: 'hero-banner__description',
                                        value: content.description,
                                        onChange: (value) => updateContent('description', value),
                                        placeholder: __('Entrez la description...', 'mediapilote')
                                    }),
                                    wp.element.createElement('div', { className: 'hero-banner__button' },
                                        wp.element.createElement('span', { className: 'hero-banner__button-text' },
                                            content.buttonText || __('En savoir +', 'mediapilote')
                                        )
                                    )
                                )
                            )
                        ),
                        wp.element.createElement('div', { className: 'hero-banner__decorative-lines' },
                            images.map((_, index) => wp.element.createElement('span', {
                                className: 'hero-banner__line',
                                key: index
                            }))
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
