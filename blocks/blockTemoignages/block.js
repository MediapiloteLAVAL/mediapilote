(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { RichText, MediaUpload, InspectorControls } = wp.blockEditor;
    const { PanelBody, Button, TextControl, TextareaControl, ColorPalette } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/temoignages', {
        title: __('Témoignages', 'mediapilote'),
        icon: 'testimonial',
        category: 'design',
        keywords: [__('témoignages', 'mediapilote'), __('avis', 'mediapilote'), __('clients', 'mediapilote')],
        
        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { title, testimonials, backgroundColor, textColor } = attributes;

            const updateTitle = function(value) {
                setAttributes({ title: value });
            };

            const updateTestimonial = function(index, key, value) {
                const newTestimonials = testimonials.map((testimonial, i) => {
                    if (i === index) {
                        if (key === 'image') {
                            return { ...testimonial, image: { ...value } };
                        } else {
                            return { ...testimonial, [key]: value };
                        }
                    }
                    return testimonial;
                });
                setAttributes({ testimonials: newTestimonials });
            };

            const addTestimonial = function() {
                const newTestimonials = [...testimonials, {
                    image: { id: 0, url: '' },
                    name: 'Nom du témoin',
                    testimonial: 'Témoignage ici...'
                }];
                setAttributes({ testimonials: newTestimonials });
            };

            const removeTestimonial = function(index) {
                if (testimonials.length > 1) {
                    const newTestimonials = testimonials.filter((_, i) => i !== index);
                    setAttributes({ testimonials: newTestimonials });
                }
            };

            const onSelectImage = function(index, media) {
                updateTestimonial(index, 'image', { id: media.id, url: media.url });
            };

            const onRemoveImage = function(index) {
                updateTestimonial(index, 'image', { id: 0, url: '' });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, {
                        title: __('Titre', 'mediapilote'),
                        initialOpen: true
                    },
                        wp.element.createElement(TextControl, {
                            label: __('Titre de la section', 'mediapilote'),
                            value: title,
                            onChange: updateTitle
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
                    ),
                    testimonials.map((testimonial, index) => wp.element.createElement(PanelBody, {
                        title: __('Témoignage ' + (index + 1), 'mediapilote'),
                        initialOpen: index < 3
                    },
                        wp.element.createElement('div', { className: 'components-base-control', style: { marginBottom: '20px' } },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Image', 'mediapilote')
                            ),
                            testimonial.image.url ? wp.element.createElement('div', null,
                                wp.element.createElement('img', { src: testimonial.image.url, style: { maxWidth: '100%', height: 'auto' } }),
                                wp.element.createElement(Button, {
                                    isSecondary: true,
                                    onClick: () => onRemoveImage(index)
                                }, __('Supprimer l\'image', 'mediapilote'))
                            ) : wp.element.createElement(MediaUpload, {
                                onSelect: (media) => onSelectImage(index, media),
                                allowedTypes: ['image'],
                                value: testimonial.image.id,
                                render: function(obj) {
                                    return wp.element.createElement(Button, {
                                        isSecondary: true,
                                        onClick: obj.open
                                    }, __('Sélectionner une image', 'mediapilote'));
                                }
                            })
                        ),
                        wp.element.createElement(TextControl, {
                            label: __('Nom', 'mediapilote'),
                            value: testimonial.name,
                            onChange: (value) => updateTestimonial(index, 'name', value)
                        }),
                        wp.element.createElement(TextareaControl, {
                            label: __('Témoignage', 'mediapilote'),
                            value: testimonial.testimonial,
                            onChange: (value) => updateTestimonial(index, 'testimonial', value)
                        }),
                        testimonials.length > 1 ? wp.element.createElement(Button, {
                            isDestructive: true,
                            onClick: () => removeTestimonial(index),
                            style: { marginTop: '10px' }
                        }, __('Supprimer ce témoignage', 'mediapilote')) : null
                    )),
                    wp.element.createElement(PanelBody, {
                        title: __('Ajouter un témoignage', 'mediapilote'),
                        initialOpen: false
                    },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            onClick: addTestimonial
                        }, __('Ajouter un témoignage', 'mediapilote'))
                    )
                ),
                wp.element.createElement('div', { className: className + ' alignfull', style: { backgroundColor: backgroundColor, padding: '60px 20px' } },
                    wp.element.createElement('div', { className: 'container' },
                        wp.element.createElement('h2', { style: { fontSize: '28px', marginBottom: '40px', color: textColor } }, title),
                        wp.element.createElement('hr', { className: 'testimonial-separator' }),
                        wp.element.createElement('div', { style: { display: 'flex', justifyContent: 'space-around', flexWrap: 'wrap', gap: '30px' } },
                            testimonials.slice(0, 3).map((testimonial, index) => wp.element.createElement('div', {
                                key: index,
                                style: { textAlign: 'center', flex: '1', minWidth: '250px', maxWidth: '300px' }
                            },
                                wp.element.createElement('div', { style: { display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '20px', marginBottom: '20px' } },
                                    testimonial.image.url ? wp.element.createElement('img', {
                                        src: testimonial.image.url,
                                        alt: testimonial.name,
                                        style: { width: '84px', height: '84px', borderRadius: '50%', objectFit: 'cover' }
                                    }) : wp.element.createElement('div', {
                                        style: { width: '84px', height: '84px' }
                                    }),
                                    wp.element.createElement('h3', { style: { fontSize: '20px', margin: '0', color: textColor } }, testimonial.name)
                                ),
                                wp.element.createElement('p', { style: { fontSize: '16px', lineHeight: '1.5', color: textColor } }, testimonial.testimonial)
                            ))
                        ),
                        testimonials.length > 3 ? wp.element.createElement('p', { style: { textAlign: 'center', marginTop: '20px', color: '#666' } }, __('Et ' + (testimonials.length - 3) + ' autres témoignages...', 'mediapilote')) : null
                    )
                )
            );
        },

        save: function() {
            return null; // Dynamic block
        }
    });
})(window.wp);