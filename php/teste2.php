<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $html = file_get_contents("https://proceedings.science/papers-published");
    // Cria um objeto DOMDocument e carrega o HTML
    $documento = new \DOMDocument();
    $documento->loadHTML($html);

    // Obtém todos os elementos <a> (links) da página
    $domNodelist = $documento->getElementsByTagName("a");
    $linklist = [];
    foreach ($domNodelist as $link) {
      $href = $link->getAttribute("href");
      if (!empty($href)) {
          $linklist[] = $href;

          $arquivo = fopen('file.csv', 'w');
// Escrever conteúdo
foreach ($linklist as $link) {
    fputcsv($arquivo, [$link]);
}
fclose($arquivo);
      }
  }
  
  fclose($arquivo);
    
  return $linklist;
        
      
}
  }

