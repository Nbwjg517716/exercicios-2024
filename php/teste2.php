<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns an array with the data.
   */
  public function scrap(): array {
    $html = file_get_contents("https://proceedings.science/papers-published");
    libxml_use_internal_errors(true);
    $documento = new \DOMDocument();
    $documento->loadHTML($html);

    $domNodelist = $documento->getElementsByTagName("a");
    $linklist = [];
    
    foreach ($domNodelist as $link) {
        $href = $link->getAttribute("href");
        if (!empty($href)) {
            $linklist[] = $href;
        }
    }
    
    $this->writeLinksToCSV($linklist);

    return [ 
      new Paper(
        123,
        'The Nobel Prize in Physiology or Medicine 2023',
        'Nobel Prize',
        [
          new Person('Katalin Karikó', 'Szeged University'),
          new Person('Drew Weissman', 'University of Pennsylvania'),
        ]
      ),
    ];
  }

  /**
   * Writes the links to a CSV file.
   */
  private function writeLinksToCSV(array $links): void {
    $arquivo = fopen('file.csv', 'w');
    
    // Verifica se o arquivo CSV foi aberto corretamente
    if ($arquivo === false) {
        echo "Erro ao abrir o arquivo CSV.";
        exit;
    }
    
    // Escreve os links no arquivo CSV
    foreach ($links as $link) {
        if (fputcsv($arquivo, [$link]) === false) {
            echo "Erro ao escrever no arquivo CSV.";
            fclose($arquivo);
            exit;
        }
    }
    
    // Fecha o arquivo CSV
    fclose($arquivo);
    
    echo "Os links foram escritos no arquivo CSV com sucesso.";
  }
}
