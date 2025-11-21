(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, MediaUpload } = wp.blockEditor;
    const { PanelBody, Button, TextControl, RangeControl } = wp.components;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;

    registerBlockType('mediapilote/carte', {
        title: __('Bloc Carte', 'mediapilote'),
        icon: 'location',
        category: 'design',
        keywords: [__('carte', 'mediapilote'), __('map', 'mediapilote'), __('leaflet', 'mediapilote')],

        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { height, markerImage } = attributes;

            const onSelectMarkerImage = function(media) {
                setAttributes({
                    markerImage: {
                        url: media.url,
                        id: media.id
                    }
                });
            };

            const onRemoveMarkerImage = function() {
                setAttributes({
                    markerImage: {
                        url: '',
                        id: 0
                    }
                });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(InspectorControls, null,
                    wp.element.createElement(PanelBody, {
                        title: __('Paramètres de la carte', 'mediapilote'),
                        initialOpen: true
                    },
                        wp.element.createElement(RangeControl, {
                            label: __('Hauteur de la carte', 'mediapilote'),
                            value: height,
                            onChange: (value) => setAttributes({ height: value }),
                            min: 200,
                            max: 800,
                            step: 10
                        }),
                        wp.element.createElement('div', { className: 'components-base-control' },
                            wp.element.createElement('label', { className: 'components-base-control__label' },
                                __('Image du marqueur', 'mediapilote')
                            ),
                            wp.element.createElement(MediaUpload, {
                                onSelect: onSelectMarkerImage,
                                allowedTypes: ['image'],
                                value: markerImage.id,
                                render: function(obj) {
                                    return wp.element.createElement('div', { style: { margin: '10px 0', background: '#f8f8f8', padding: '8px', borderRadius: '4px', textAlign: 'center' } },
                                        markerImage.url ?
                                            wp.element.createElement('img', {
                                                src: markerImage.url,
                                                alt: __('Marqueur', 'mediapilote'),
                                                style: { maxWidth: '50px', maxHeight: '50px', display: 'block', margin: '0 auto' }
                                            }) :
                                            wp.element.createElement(Button, {
                                                className: 'button button-large',
                                                onClick: obj.open
                                            }, __('Choisir une image', 'mediapilote'))
                                    );
                                }
                            }),
                            markerImage.url && wp.element.createElement(Button, {
                                onClick: onRemoveMarkerImage,
                                isDestructive: true,
                                style: { marginTop: '10px' }
                            }, __('Supprimer l\'image', 'mediapilote'))
                        )
                    ),
                    wp.element.createElement(PanelBody, {
                        title: __('Informations', 'mediapilote')
                    },
                        wp.element.createElement('p', null, __('Les adresses sont récupérées automatiquement depuis les options ACF "Coordonnées". Assurez-vous que les entreprises ont des coordonnées latitude et longitude définies.', 'mediapilote'))
                    )
                ),
                wp.element.createElement('div', {
                    className: className + ' wp-block-mediapilote-carte',
                    style: { height: height + 'px', background: '#f0f0f0', display: 'flex', alignItems: 'center', justifyContent: 'center', border: '1px dashed #ccc' }
                },
                    wp.element.createElement('div', { style: { textAlign: 'center' } },
                        wp.element.createElement('p', null, __('Carte Leaflet - Prévisualisation', 'mediapilote')),
                        wp.element.createElement('p', null, __('Hauteur: ', 'mediapilote') + height + 'px'),
                        wp.element.createElement('p', null, __('Les adresses sont récupérées depuis les options ACF.', 'mediapilote'))
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