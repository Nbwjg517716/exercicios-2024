<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and saves it to an Excel table.
   */
  public function scrapAndSaveToExcel(): void {
    // Carrega o conteúdo HTML da página desejada
    $html = file_get_contents("https://proceedings.science/papers-published");
    
    // Verifica se o HTML foi carregado corretamente
    if ($html === false) {
        echo "Erro ao acessar a página.";
        exit;
    }
    
    // Cria um objeto DOMDocument e carrega o HTML
    $documento = new \DOMDocument();
    $documento->loadHTML($html);

    // Obtém todos os elementos <a> (links) da página
    $domNodelist = $documento->getElementsByTagName("a");
    $linklist = [];
    
    // Itera sobre os links e extrai seus atributos href
    foreach ($domNodelist as $link) {
        $href = $link->getAttribute("href");
        if (!empty($href)) {
            $linklist[] = $href;
        }
    }
    
    // Escreve os links em uma tabela Excel
    $this->writeLinksToExcel($linklist);

    echo "Os links foram salvos em uma tabela Excel com sucesso.";
  }

  /**
   * Writes the links to an Excel table.
   */
  private function writeLinksToExcel(array $links): void {
    // Cria um novo objeto PHPExcel
    $objPHPExcel = new \PHPExcel();

    // Define as colunas
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Links');
    $row = 2;

    // Escreve os links nas células
    foreach ($links as $link) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $link);
        $row++;
    }

    // Cria um escritor para salvar o arquivo Excel
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    // Salva o arquivo Excel
    $objWriter->save('links.xlsx');
  }
}

