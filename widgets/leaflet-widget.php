<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly


class Leaflet_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'leaflet-map';
    }

    public function get_title()
    {
        return __('Leaflet Map', 'stemon');
    }

    public function get_icon()
    {
        return 'fa fa-map';
    }

    public function get_categories()
    {
        return ['stemon-widgets'];
    }

    public function _register_controls()
    {

        //! Content Tab
        $this->start_controls_section(
            'stemon_content',
            [
                'label' => __('Content', 'stemon'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'api_key',
            [
                'label' =>  __('API Key', 'stemon'),
                'type'   => Elementor\Controls_Manager::TEXT,
                'description'   => __('Get key from <a href="https://www.mapbox.com" target="_blank">Mapbox</a>'),
                'placeholder'   =>  __('Add API Key', 'stemon'),
                'separator' =>  'after',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'lat',
            [
                'label' =>  __('Map Latitude', 'stemon'),
                'type'   => Elementor\Controls_Manager::TEXT,
                'placeholder'   => '10.3008885',
                'default'   => 10.3008885,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'lon',
            [
                'label' =>  __('Map Longitude', 'stemon'),
                'type'   => Elementor\Controls_Manager::TEXT,
                'placeholder'   => '-85.8468247',
                'default'   => -85.8468247,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'get_coord',
            [
                'label' =>  __('Get Coordinates', 'stemon'),
                'type'   => Elementor\Controls_Manager::SWITCHER,
                'description' => 'Allows to get the coordinates on location click',
                'label_on' => __('Yes', 'stemon'),
                'label_off' => __('No', 'stemon'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'after',
                'frontend_available' => true
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'pin_title',
            [
                'label' => __('Pin Location', 'stemon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Add Pin Name', 'stemon'),
                'label_block' => true,
                'frontend_available' => true
            ]
        );

        $repeater->add_control(
            'pin_content',
            [
                'label' => __('Content', 'stemon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => __('Add Content About Location', 'stemon'),
                'show_label' => false,
                'frontend_available' => true
            ]
        );

        $repeater->add_control(
            'pin_lat',
            [
                'label' =>  __('Pin Latitude', 'stemon'),
                'type'   => Elementor\Controls_Manager::TEXT,
                'frontend_available' => true
            ]
        );

        $repeater->add_control(
            'pin_lon',
            [
                'label' =>  __('Pin Longitude', 'stemon'),
                'type'   => Elementor\Controls_Manager::TEXT,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'pins',
            [
                'label' => __('Add New Pins', 'stemon'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'frontend_available' => true,
                'default'   => [
                    [
                        'pin_title' => __('Title', 'stemon'),
                        'pin_content'   => __('Write Content', 'stemon'),
                        'pin_lat'       => __('10.287389', 'stemon'),
                        'pin_lon'       => __('-85.85079', 'stemon')
                    ]
                ],
                'title_field' => '{{{ pin_title }}}',
            ]
        );



        $this->end_controls_section();


        //! Style Tab
        $this->start_controls_section(
            'stemon-style',
            [
                'label' => __('Style', 'stemon'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'map_height',
            [
                'label' =>  __('Map Height', 'stemon'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'description' => 'Value unit in "px". Max value is 2000',
                'size_units' => ['px'],
                'default' => [
                    'size' => 400,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 2000,
                        'step' => 50
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .stemon-leaflet-map' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' =>  __('Initial Zoom', 'stemon'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 13,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'map_style',
            [
                'label' =>  __('Map Style', 'stemon'),
                'type'   =>  \Elementor\Controls_Manager::SELECT,
                'options'   =>  [
                    'streets' => __('Streets', 'stemon'),
                    'satellite' =>  __('Satellite', 'stemon')
                ],
                'default' => 'streets',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'pin',
            [
                'label' => __('Choose Pin', 'stemon'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => plugin_dir_url('leaflet-elementor-addon') . 'leaflet-elementor-addon/assets/img/pin.svg',
                ],
                'separator' =>  'before',
                'frontend_available' => true
            ]
        );


        $this->add_control(
            'pin_size',
            [
                'label' => __('Pin Size', 'stemon'),
                'type'  => \Elementor\Controls_Manager::NUMBER,
                'default'   => '20',
                'placeholder'   => '20px',
                'separator' =>  'after',
                'frontend_available' => true
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        echo '<div id="stemonLeafletMap" class="stemon-leaflet-map"></div>';
    }

    protected function _content_template()
    { }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_style('leaflet-style', plugin_dir_url('leaflet-elementor-addon') . 'leaflet-elementor-addon/assets/leaflet/leaflet.css');
        wp_register_style('stemon-leaflet-stylesheet', plugin_dir_url('leaflet-elementor-addon') . 'leaflet-elementor-addon/assets/stemon/stemon-leaflet.css', ['leaflet-style']);

        wp_register_script('leaflet-js', plugin_dir_url('leaflet-elementor-addon') . 'leaflet-elementor-addon/assets/leaflet/leaflet.js');
        wp_register_script('stemon-leaflet-js', plugin_dir_url('leaflet-elementor-addon') . 'leaflet-elementor-addon/assets/stemon/stemon-leaflet.js', ['elementor-frontend', 'leaflet-js'], '1.0.0', true);
    }

    public function get_script_depends()
    {
        return ['stemon-leaflet-js'];
    }

    public function get_style_depends()
    {
        return ['stemon-leaflet-stylesheet'];
    }
}
