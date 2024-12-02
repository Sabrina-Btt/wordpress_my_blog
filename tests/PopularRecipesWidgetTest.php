<?php

use PHPUnit\Framework\TestCase;

class PopularRecipesWidgetTest extends TestCase {

    // Setup do ambiente do teste
    public function setUp(): void {
        // Carregar o ambiente do WordPress e o widget
        require_once 'wp-load.php'; 
        include_once 'wp-content/themes/yummy-bites/inc/popular-recipes.php'; 

        // Registrar o widget no WordPress
        register_popular_recipes_widget();
    }

    // Testando a renderização do widget
    public function testWidgetOutput() {
        // Criação de um mock para o $args, que simula o contexto do widget
        $args = [
            'before_widget' => '<div class="widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2>',
            'after_title'   => '</h2>',
        ];

        // Exemplo de dados para o instance do widget
        $instance = [
            'title' => 'Receitas Mais Populares'
        ];

        // Criação de uma instância do widget
        $widget = new Popular_Recipes_Widget();

        // Iniciar captura de saída
        ob_start();

        // Chamar o método widget que irá gerar o HTML
        $widget->widget($args, $instance);

        // Capturar o conteúdo gerado
        $output = ob_get_clean();

        // Verificar se o título do widget aparece na saída
        $this->assertStringContainsString('<h2>Receitas Mais Populares</h2>', $output);
        
        // Verificar se o HTML do widget foi gerado corretamente
        $this->assertStringContainsString('<div class="widget">', $output);
    }

    // Testando o método que lida com a atualização do título
    public function testUpdateWidgetTitle() {
        $widget = new Popular_Recipes_Widget();

        // Simulando dados da instância
        $old_instance = ['title' => 'Receitas Mais Populares'];
        $new_instance = ['title' => 'Top Recipes'];

        // Chamando o método update
        $updated_instance = $widget->update($new_instance, $old_instance);

        // Verificando se o título foi atualizado
        $this->assertEquals('Top Recipes', $updated_instance['title']);
    }
}
