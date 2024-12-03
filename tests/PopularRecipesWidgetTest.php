<?php

use PHPUnit\Framework\TestCase;

class PopularRecipesWidgetTest extends TestCase {
    private $widget;

    public function setUp(): void {
        require_once 'wp-load.php'; 
        include_once 'wp-content/themes/yummy-bites/inc/popular-recipes.php'; 

        register_popular_recipes_widget();
        $this->widget = new Popular_Recipes_Widget();
    }

    public function testWidgetTitle() {
        $args = [
            'before_widget' => '<div>',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>',
        ];
        
        $instance = ['title' => 'Receitas Mais Populares'];
        
        ob_start();
        $this->widget->widget($args, $instance);
        $output = ob_get_clean();
        
        $this->assertStringContainsString('<h2>Receitas Mais Populares</h2>', $output);
    }

    public function testWidgetDisplaysPopularRecipes() {
        $args = [
            'before_widget' => '<div>',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>',
        ];
        
        $popular_recipes = [
            (object) ['ID' => 1, 'post_title' => 'Cupcake de Morango', 'post_name' => 'cupcake-morango'],
            (object) ['ID' => 2, 'post_title' => 'Palitos de Queijo Empanados', 'post_name' => 'palitos-de-queijo'],
            (object) ['ID' => 3, 'post_title' => 'Salada Caesar', 'post_name' => 'salada-caesar']
        ];
        
        global $wpdb;
        $wpdb = $this->createMock(WPDB::class);
        $wpdb->expects($this->once())
            ->method('get_results')
            ->willReturn($popular_recipes);
        
        ob_start();
        $this->widget->widget($args, ['title' => 'Receitas Mais Populares']);
        $output = ob_get_clean();

        $this->assertStringContainsString('<ul>', $output);
        $this->assertStringContainsString('</ul>', $output);
        $this->assertStringContainsString('Cupcake de Morango', $output);
        $this->assertStringContainsString('Palitos de Queijo Empanados', $output);
        $this->assertStringContainsString('Salada Caesar', $output);
    }
}



