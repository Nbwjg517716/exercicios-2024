<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Scrapes paper information from the HTML and returns an array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $html = file_get_contents("https://proceedings.science/papers-published");
    // Cria um objeto DOMDocument e carrega o HTML
    $documento = new \DOMDocument();
    $documento->loadHTML($html);

    // Obtém todos os elementos <article> da página
    $articles = $documento->getElementsByTagName("article");
    $papers = [];

    foreach ($articles as $article) {
      $paper = new Paper();

      // Extrai o título do artigo
      $titleElement = $article->getElementsByTagName("h2")->item(0);
      $paper->setTitle($titleElement->nodeValue);

      // Extrai o nome do autor
      $authorElement = $article->getElementsByTagName("span")->item(0);
      $paper->setAuthor($authorElement->nodeValue);

      // Extrai o resumo do artigo
      $abstractElement = $article->getElementsByTagName("p")->item(0);
      $paper->setAbstract($abstractElement->nodeValue);

      // Extrai o link para o artigo completo
      $linkElement = $article->getElementsByTagName("a")->item(0);
      $paper->setLink($linkElement->getAttribute("href"));

      $papers[] = $paper;
    }
    
    // Salva os dados em um arquivo CSV
    $arquivo = fopen('papers.csv', 'w');
    foreach ($papers as $paper) {
        fputcsv($arquivo, [$paper->getTitle(), $paper->getAuthor(), $paper->getAbstract(), $paper->getLink()]);
    }
    fclose($arquivo);

    return $papers;
  }
}


