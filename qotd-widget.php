<?php

class QOTD_Widget extends \Elementor\Widget_Base
{   
    //  Make elementor identify this widget as 'quote_of_the_day'
    public function get_name()
    {
        return 'quote_of_the_day';
    }

    //  Make the title of QOTD_Widget as "Quote of the Day" on the elementor panel on page edit
    public function get_title()
    {
        return __('Quote of the Day', 'qotd_elementor');
    }

    //  Make the icon of QOTD_Widget as the quote icon on the elementor panel on page edit
    public function get_icon()
    {
        return 'eicon-editor-quote';
    }

    //  Categorized the QOTD_Widget under the general categories on the elementor panel on page edit
    public function get_categories()
    {
        return ['general'];
    }

    //  Pre-defined quotes that will be cycled on display every day
    private $quotes = [
        'Where expectations end… peace begins there',
        'Waking up early is always beneficial… be it from sleep or ego or delusion.',
        'If your voice is high, only a few people will hear. If your thought is high, then many people will listen.',
        'The bad thing is that the time is short… and the good thing is that there is still some time..',
        'Always make a total effort, even when the odds are against you.',
    ];

    protected function _register_controls()
    {
        //  Add a style tab on the elementor panel on page edit
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'qotd_elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        //  Add a typography settings inside the style tab on the elementor panel on page edit
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .quote',
            ]
        );

        //  Add a text shadow settings inside the style tab on the elementor panel on page edit
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'label' => __('Text Shadow', 'qotd_elementor'),
                'selector' => '{{WRAPPER}} .quote',
            ]
        );   
        
        //  Add a box shadow settings inside the style tab on the elementor panel on page edit
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'qotd_elementor'),
                'selector' => '{{WRAPPER}} .quote-container',
            ]
        );

        //  Add a background color settings inside the style tab on the elementor panel on page edit
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'qotd_elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quote-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        //  Add a text color settings inside the style tab on the elementor panel on page edit
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'qotd_elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    //  Renders the output that will be displayed on the page
    protected function render()
    {
        $current_day = date('N'); // Get the current day (1-7, 1 for Monday, 7 for Sunday)
        $quote_index = ($current_day - 1) % count($this->quotes); // Calculate the index of the quote based on the current day
        $quote_text = $this->quotes[$quote_index]; // Get the quote text for the current day
    
        ?>
        <div class="quote-container">
            <p class="quote" <?php echo $this->get_render_attribute_string('quote_typography'); ?>><?php echo $quote_text; ?></p>
        </div>
        <?php
    }
    

    //  Renders the output that will be displayed on the preview edit page
    protected function content_template()
    { 
        $current_day = date('N'); // Get the current day (1-7, 1 for Monday, 7 for Sunday)
        $quote_index = ($current_day - 1) % count($this->quotes); // Calculate the index of the quote based on the current day
        $quote_text = $this->quotes[$quote_index]; // Get the quote text for the current day
    
        ?>
        <div class="quote-container">
            <p class="quote" <?php echo $this->get_render_attribute_string('quote_typography'); ?>><?php echo $quote_text; ?></p>
        </div>
        <?php
    }
}

// register the plugin as a widget type in Elementor
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new QOTD_Widget());
