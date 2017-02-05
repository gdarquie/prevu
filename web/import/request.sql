-- Import Prévu de koha > prevu | ajouter une bibliothèque + trouver un moyen de donner un id aux livres peut-être avec le code 10 de MARC 21

-- ccodes / ajouter une condition pour vérfier que les codes n'existent pas déjà, ajouter la biblio ainsi que la date de création et de changement
INSERT INTO prevu.code(`code`) (SELECT DISTINCT(ccode) FROM koha.items WHERE ccode !=" " OR ccode IS NOT NULL); -- un where pour ajouter dans une biblio

-- itemtypes
INSERT INTO prevu.itemtype(itemtype)(SELECT distinct itemtype FROM koha.biblioitems WHERE itemtype IS NOT NULL);

-- books
INSERT INTO prevu.book(`id_book`, `title`, `author`, `publicationyear`,`isbn`)(SELECT biblio.biblionumber, biblio.title, biblio.author, biblioitems.publicationyear, biblioitems.isbn FROM koha.biblio INNER JOIN koha.biblioitems ON biblio.biblionumber = biblioitems.biblionumber);

-- borrowers
INSERT INTO `borrower`(`id_borrower`, `yearofbirth`) (SELECT borrowers.borrowernumber, YEAR(borrowers.dateofbirth) FROM koha.borrowers);

-- prêts (sélection d'un épisode de temps précis)
INSERT INTO prevu.issue(`idborrower`, `sex`, `datedue`, `issuedate`, `returndate`, `renewals`, `idbook`, `niveau`) (SELECT old_issues.borrowernumber, borrowers.sex, old_issues.date_due, old_issues.issuedate, old_issues.returndate, old_issues.renewals, items.biblionumber, borrower_attributes.attribute FROM koha.old_issues INNER JOIN koha.items ON old_issues.itemnumber = items.itemnumber LEFT JOIN koha.borrowers ON borrowers.borrowernumber = old_issues.borrowernumber LEFT JOIN koha.borrower_attributes ON borrower_attributes.borrowernumber = borrowers.borrowernumber WHERE borrower_attributes.code = "niveau" AND returndate  BETWEEN '2012-01-01 00:00:00' AND '2016-03-30 23:59:59');
UPDATE prevu.issue INNER JOIN koha.borrower_attributes ON borrower_attributes.borrowernumber = issue.idborrower SET issue.ufr = borrower_attributes.attribute WHERE borrower_attributes.code = "ufr";
UPDATE prevu.issue INNER JOIN koha.borrower_attributes ON borrower_attributes.borrowernumber = issue.idborrower SET issue.etape = borrower_attributes.attribute WHERE borrower_attributes.code = "etape";
UPDATE `book` SET `issues`=  (SELECT COUNT(*) FROM issue WHERE issue.idbook = book.id_book GROUP BY idbook );

-- renewals
UPDATE `book` SET renewals= (SELECT SUM(renewals) as nb FROM issue WHERE issue.idbook = book.id_book GROUP BY idbook );

-- addition des prêts et des renewals pour avoir les prêts totaux
UPDATE `book` SET total_issues= (renewals+issues);

----------------------
-- TO DO --
----------------------

--Insertion des auteurs

-- Méthode 1
-- Sélection des données main_author
SELECT EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="a"]'), EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="b"]'),  EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="f"]') FROM biblioitems LIMIT 100;
-- Insertion de tous les nouveaux auteurs (trop longue ?)
SELECT EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="a"]') as a, EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="b"]') as b,  EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="f"]') as c FROM biblioitems GROUP BY a, b, c LIMIT 100

-- Méthode 2
-- Insertion de tous les auteurs dans une table intermédiaire

-- Création de la table
CREATE TABLE import_author (
    id INT(33) UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(500) ,
    lastname VARCHAR(500),
    dates VARCHAR(25),
    biblionumber INT(25),
    PRIMARY KEY (id)
)
ENGINE=INNODB;

-- Insertion dans la table de tous les auteurs [préciser le select avec un where et récupérer les biblionumbers]
INSERT INTO import_author (biblionumber, firstname, lastname, dates ) (SELECT biblionumber, EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="a"]')as a, EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="b"]') as b,  EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="f"]') as f FROM biblioitems WHERE EXTRACTVALUE(marcxml,'//datafield[@tag="700"]/subfield[@code="a"]') != "")

-- Selection des auteurs -- il faut donner un id aux auteurs et désambiguiser / donner un id à tous les auteurs
SELECT DISTINCT firstname, lastname, dates FROM import_author WHERE firstname != ""


--Create table : firstname, lastname,dates, biblionumber,(idnotice?)
--Import auteurs dans la table intermédiaire
--Insert d'auteurs dans table vide authors (INSERT DISTINCT() la sélection Disctinct)


--import des auteurs secondaires

-- import des œuvres

-- import de l'auteur principal 700, nom et prénom (a et b), dates (c) [il n'y a qu'un auteur principal]


-- import des vedettes matières
-- import des langues


SELECT EXTRACTVALUE(marcxml,'//datafield[@tag="210 "]/subfield[@code="d"]') FROM biblioitems



--- Notice Goncourt

<?xml version="1.0" encoding="UTF-8"?>
<record
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.loc.gov/MARC21/slim http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd"
    xmlns="http://www.loc.gov/MARC21/slim">

  <leader>00916cam  2200277   4500</leader>
  <controlfield tag="001">6283</controlfield>
  <controlfield tag="005">20151202012026.0</controlfield>
  <controlfield tag="009">008849773</controlfield>
  <datafield tag="010" ind1=" " ind2=" ">
    <subfield code="a">2-906164-28-3</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">sib0305401</subfield>
  </datafield>
  <datafield tag="036" ind1=" " ind2=" ">
    <subfield code="a">7558</subfield>
  </datafield>
  <datafield tag="090" ind1=" " ind2=" ">
    <subfield code="a">6283</subfield>
  </datafield>
  <datafield tag="099" ind1=" " ind2=" ">
    <subfield code="t">MONOG</subfield>
  </datafield>
  <datafield tag="100" ind1=" " ind2=" ">
    <subfield code="a">20120119              frey50       </subfield>
  </datafield>
  <datafield tag="101" ind1="0" ind2=" ">
    <subfield code="a">fre</subfield>
  </datafield>
  <datafield tag="102" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
  </datafield>
  <datafield tag="200" ind1="1" ind2=" ">
    <subfield code="a">La fille Elisa</subfield>
    <subfield code="e">roman</subfield>
    <subfield code="f">Edmond [et Jules] de Goncourt ; présentation et notes de Gérard Delaisement</subfield>
  </datafield>
  <datafield tag="210" ind1=" " ind2=" ">
    <subfield code="a">Paris</subfield>
    <subfield code="c">La Boîte à Documents</subfield>
    <subfield code="d">1990</subfield>
  </datafield>
  <datafield tag="215" ind1=" " ind2=" ">
    <subfield code="a">185 p.</subfield>
    <subfield code="d">22 cm. -</subfield>
  </datafield>
  <datafield tag="702" ind1=" " ind2="1">
    <subfield code="a">Delaisement</subfield>
    <subfield code="b">Gérard</subfield>
    <subfield code="c">éd.</subfield>
  </datafield>
  <datafield tag="700" ind1=" " ind2="1">
    <subfield code="9">641659</subfield>
    <subfield code="7">ba0yba0y</subfield>
    <subfield code="a">Goncourt</subfield>
    <subfield code="b">Edmond de</subfield>
    <subfield code="f">1822-1896</subfield>
  </datafield>
  <datafield tag="701" ind1=" " ind2="1">
    <subfield code="9">641660</subfield>
    <subfield code="7">ba0yba0y</subfield>
    <subfield code="a">Goncourt</subfield>
    <subfield code="b">Jules de</subfield>
    <subfield code="f">1830-1870</subfield>
    <subfield code="3">027835995</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SU</subfield>
    <subfield code="d">20010705</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="c">19991010</subfield>
  </datafield>
  <datafield tag="990" ind1=" " ind2=" ">
    <subfield code="a">VF 148098 LIP</subfield>
    <subfield code="b">930662101m1</subfield>
    <subfield code="d">1</subfield>
    <subfield code="e">1</subfield>
    <subfield code="1">cdu-pd</subfield>
    <subfield code="c">840"18" GON 7</subfield>
    <subfield code="x">930662101a/</subfield>
    <subfield code="5">05.10.1999/932101/dev</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">148098+1</subfield>
    <subfield code="y">025065876</subfield>
    <subfield code="q">840"18" GON 7</subfield>
  </datafield>
</record>


----
-- 1000 plateaux
--------

--id =  97756


<?xml version="1.0" encoding="UTF-8"?>
<record
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.loc.gov/MARC21/slim http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd"
    xmlns="http://www.loc.gov/MARC21/slim">

  <leader>03468cam0 2201165   4500</leader>
  <controlfield tag="001">97756</controlfield>
  <controlfield tag="005">20151002012028.0</controlfield>
  <controlfield tag="009">000337404</controlfield>
  <datafield tag="010" ind1=" " ind2=" ">
    <subfield code="a">2-7073-0307-0</subfield>
    <subfield code="b">br.</subfield>
    <subfield code="d">61 FRF</subfield>
  </datafield>
  <datafield tag="010" ind1=" " ind2=" ">
    <subfield code="a">978-2-7073-0307-3</subfield>
    <subfield code="b">br.</subfield>
    <subfield code="d">29.75 EUR</subfield>
  </datafield>
  <datafield tag="020" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
    <subfield code="b">08017285</subfield>
  </datafield>
  <datafield tag="021" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
    <subfield code="b">D.L. 80-24544</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">011696109</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">004352971</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">010895248</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">05334748X</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">108673154</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">ocm09059558</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">ocm09827095</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">frBN023321613</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">AIC02390297-2</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">sib0617660</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">DYNIX_BUCAY_30905</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">urouen25281</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">sib0489965</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">DYNIX_BUNAN_24258</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">CAEND010071039001</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">sib1242996</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">frBN000988131</subfield>
  </datafield>
  <datafield tag="036" ind1=" " ind2=" ">
    <subfield code="a">194582</subfield>
  </datafield>
  <datafield tag="073" ind1=" " ind2="0">
    <subfield code="a">9782707303073</subfield>
  </datafield>
  <datafield tag="090" ind1=" " ind2=" ">
    <subfield code="a">97756</subfield>
  </datafield>
  <datafield tag="099" ind1=" " ind2=" ">
    <subfield code="t">MONOG</subfield>
  </datafield>
  <datafield tag="100" ind1=" " ind2=" ">
    <subfield code="a">19800919h19801980k  y0frey5003    ba</subfield>
  </datafield>
  <datafield tag="101" ind1="0" ind2=" ">
    <subfield code="a">fre</subfield>
    <subfield code="e">fre</subfield>
    <subfield code="f">fre</subfield>
    <subfield code="g">fre</subfield>
  </datafield>
  <datafield tag="102" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
  </datafield>
  <datafield tag="105" ind1=" " ind2=" ">
    <subfield code="a">a   a   000yy</subfield>
  </datafield>
  <datafield tag="106" ind1=" " ind2=" ">
    <subfield code="a">r</subfield>
  </datafield>
  <datafield tag="200" ind1="1" ind2=" ">
    <subfield code="a">Mille plateaux</subfield>
    <subfield code="b">Texte imprimé</subfield>
    <subfield code="f">Gilles Deleuze, Félix Guattari</subfield>
  </datafield>
  <datafield tag="210" ind1=" " ind2=" ">
    <subfield code="a">Paris</subfield>
    <subfield code="c">les Ed. de Minuit</subfield>
    <subfield code="d">impr. 1980</subfield>
    <subfield code="e">61-Alençon</subfield>
    <subfield code="g">impr. Corbière et Jugain</subfield>
  </datafield>
  <datafield tag="215" ind1=" " ind2=" ">
    <subfield code="a">1 vol. (645 p.)</subfield>
    <subfield code="c">ill.</subfield>
    <subfield code="d">22 cm</subfield>
  </datafield>
  <datafield tag="225" ind1="1" ind2=" ">
    <subfield code="a">Capitalisme et schizophrénie</subfield>
    <subfield code="v">2</subfield>
  </datafield>
  <datafield tag="225" ind1="0" ind2=" ">
    <subfield code="a">Critique</subfield>
  </datafield>
  <datafield tag="305" ind1=" " ind2=" ">
    <subfield code="a">Réimpressions : 1989, 1994, 1997, 2001, 2004, 2006</subfield>
  </datafield>
  <datafield tag="320" ind1=" " ind2=" ">
    <subfield code="a">Notes bibliogr.</subfield>
  </datafield>
  <datafield tag="410" ind1=" " ind2=" ">
    <subfield code="t">Critique (Collection)</subfield>
    <subfield code="x">0768-0090</subfield>
  </datafield>
  <datafield tag="461" ind1=" " ind2=" ">
    <subfield code="t">Capitalisme et schizophrénie</subfield>
    <subfield code="v">2</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="9">734938</subfield>
    <subfield code="3">040777065</subfield>
    <subfield code="a">Schizophrénie</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Capitalism</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Philosophy</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Social psychiatry</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Psychanalyse et capitalisme</subfield>
    <subfield code="9">645981</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Psychiatrie sociale</subfield>
    <subfield code="9">632785</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Inconscient</subfield>
    <subfield code="9">623074</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Schizophrenia</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Capitalisme</subfield>
    <subfield code="9">766459</subfield>
  </datafield>
  <datafield tag="606" ind1=" " ind2=" ">
    <subfield code="a">Psychanalyse et sociologie</subfield>
    <subfield code="9">625835</subfield>
  </datafield>
  <datafield tag="675" ind1=" " ind2=" ">
    <subfield code="a">10</subfield>
    <subfield code="v">Éd. 1967</subfield>
  </datafield>
  <datafield tag="676" ind1=" " ind2=" ">
    <subfield code="a">100</subfield>
  </datafield>
  <datafield tag="680" ind1=" " ind2=" ">
    <subfield code="a">RC455</subfield>
  </datafield>
  <datafield tag="686" ind1=" " ind2=" ">
    <subfield code="a">WM 460</subfield>
    <subfield code="2">usnlm</subfield>
  </datafield>
  <datafield tag="700" ind1=" " ind2="1">
    <subfield code="9">579644</subfield>
    <subfield code="7">ba0yba0y</subfield>
    <subfield code="a">Deleuze</subfield>
    <subfield code="b">Gilles</subfield>
    <subfield code="f">1925-1995</subfield>
    <subfield code="3">026820552</subfield>
  </datafield>
  <datafield tag="701" ind1=" " ind2="1">
    <subfield code="9">610624</subfield>
    <subfield code="7">ba0yba0y</subfield>
    <subfield code="a">Guattari</subfield>
    <subfield code="b">Félix</subfield>
    <subfield code="f">1930-1992</subfield>
    <subfield code="3">02690554X</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">Abes</subfield>
    <subfield code="c">20090309</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">US</subfield>
    <subfield code="b">OCLC</subfield>
    <subfield code="g">AACR2</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="2">
    <subfield code="a">FR</subfield>
    <subfield code="b">AUROC</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">US</subfield>
    <subfield code="b">OCLC</subfield>
    <subfield code="g">AACR2</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="2">
    <subfield code="a">FR</subfield>
    <subfield code="b">AUROC</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">BN</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">BN</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">FR</subfield>
    <subfield code="b">AIC</subfield>
    <subfield code="c">20010720</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="0">
    <subfield code="a">FR</subfield>
    <subfield code="b">141182101</subfield>
    <subfield code="c">19971124</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">Abes</subfield>
    <subfield code="c">20080412</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">US</subfield>
    <subfield code="b">OCLC</subfield>
    <subfield code="g">AACR2</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="2">
    <subfield code="a">FR</subfield>
    <subfield code="b">AUROC</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">US</subfield>
    <subfield code="b">OCLC</subfield>
    <subfield code="g">AACR2</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="2">
    <subfield code="a">FR</subfield>
    <subfield code="b">AUROC</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">BN</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">BN</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">FR</subfield>
    <subfield code="b">AIC</subfield>
    <subfield code="c">20010720</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="0">
    <subfield code="a">FR</subfield>
    <subfield code="b">141182101</subfield>
    <subfield code="c">19971124</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SU</subfield>
    <subfield code="d">20010705</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="c">19991219</subfield>
  </datafield>
  <datafield tag="915" ind1=" " ind2=" ">
    <subfield code="5">930662101:305760416</subfield>
    <subfield code="b">025482156</subfield>
  </datafield>
  <datafield tag="930" ind1=" " ind2=" ">
    <subfield code="5">930662101:305760416</subfield>
    <subfield code="b">930662101</subfield>
    <subfield code="a">1"19" DEL 7 Mil</subfield>
    <subfield code="j">u</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">215974</subfield>
    <subfield code="y">025279559</subfield>
    <subfield code="q">1"19" DEL 7</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">215976</subfield>
    <subfield code="y">025279557</subfield>
    <subfield code="q">1"19" DEL 7</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">482155</subfield>
    <subfield code="y">025482155</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7</subfield>
    <subfield code="y">025279560</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7 Mil</subfield>
    <subfield code="y">025338000</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7 Mil</subfield>
    <subfield code="y">025482156</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7 Mil</subfield>
    <subfield code="y">025514372</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7 Mil</subfield>
    <subfield code="y">025514373</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">1"19" DEL 7 Mil</subfield>
    <subfield code="y">025563890</subfield>
  </datafield>
</record>


----
-- Vieux
------

-- Récupération des données exemplaires : nb d'exemplaires, ccode &



-- Insert des nombres d'exemplaires

  -- Sélection des nombres d'items par biblionumber
SELECT  DISTINCT(biblio.biblionumber), biblio.title, COUNT(DISTINCT(items.itemnumber)) as nb FROM biblio LEFT JOIN items ON items.biblionumber = biblio.biblionumber GROUP BY biblio.biblionumber ORDER BY nb DESC LIMIT 100

  -- UPDATE du nombre d'exemplaire dans Prévu (ajouter un champ exemplaires)


-- Travail sur les ccode

SELECT items.ccode, COUNT(items.ccode) as nb FROM items INNER JOIN biblio ON biblio.biblionumber = items.biblionumber WHERE biblio.title LIKE "%ecran" GROUP BY items.ccode
ORDER BY nb DESC

--


<?xml version="1.0" encoding="UTF-8"?>
<record
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.loc.gov/MARC21/slim http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd"
    xmlns="http://www.loc.gov/MARC21/slim">

  <leader>02895cam  2200721   4500</leader>
  <controlfield tag="001">351</controlfield>
  <controlfield tag="005">20150605125201.0</controlfield>
  <controlfield tag="009">000800244</controlfield>
  <datafield tag="010" ind1=" " ind2=" ">
    <subfield code="b">210 F</subfield>
  </datafield>
  <datafield tag="010" ind1=" " ind2=" ">
    <subfield code="a">2-7118-0283-3</subfield>
    <subfield code="d">210F</subfield>
  </datafield>
  <datafield tag="020" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
    <subfield code="b">08505232</subfield>
  </datafield>
  <datafield tag="021" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
    <subfield code="b">D.L. 84-32762</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">005260442</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">ocm12582460</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="z">ocm11762657</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">frBN002466769</subfield>
  </datafield>
  <datafield tag="035" ind1=" " ind2=" ">
    <subfield code="a">sib0060806</subfield>
  </datafield>
  <datafield tag="036" ind1=" " ind2=" ">
    <subfield code="a">473</subfield>
  </datafield>
  <datafield tag="073" ind1=" " ind2=" ">
    <subfield code="a"> 9782711802838</subfield>
  </datafield>
  <datafield tag="090" ind1=" " ind2=" ">
    <subfield code="a">351</subfield>
  </datafield>
  <datafield tag="099" ind1=" " ind2=" ">
    <subfield code="t">MONOG</subfield>
  </datafield>
  <datafield tag="100" ind1=" " ind2=" ">
    <subfield code="a">19840420d1984    m  y0frey5003    ba</subfield>
  </datafield>
  <datafield tag="101" ind1="0" ind2=" ">
    <subfield code="a">fre</subfield>
  </datafield>
  <datafield tag="102" ind1=" " ind2=" ">
    <subfield code="a">FR</subfield>
  </datafield>
  <datafield tag="105" ind1=" " ind2=" ">
    <subfield code="a">a   zz  00|z|</subfield>
  </datafield>
  <datafield tag="106" ind1=" " ind2=" ">
    <subfield code="a">r</subfield>
  </datafield>
  <datafield tag="200" ind1="1" ind2=" ">
    <subfield code="a">Diderot et l'art, de Boucher à David</subfield>
    <subfield code="e">les Salons, 1759-1781</subfield>
    <subfield code="e">[exposition, Paris], Hôtel de la Monnaie, 5 octobre 1984 - 6 janvier 1985</subfield>
    <subfield code="f">[organisé par l'Association française pour les célébrations nationales, la Réunion des musées nationaux et la Direction des monnaies et médailles]</subfield>
  </datafield>
  <datafield tag="210" ind1=" " ind2=" ">
    <subfield code="a">Paris</subfield>
    <subfield code="c">Éditions de la Réunion des musées nationaux</subfield>
    <subfield code="d">1984</subfield>
    <subfield code="e">Paris</subfield>
    <subfield code="g">Impr. moderne du lion</subfield>
  </datafield>
  <datafield tag="215" ind1=" " ind2=" ">
    <subfield code="a">548 p</subfield>
    <subfield code="c">ill. en noir et en coul., couv. ill. en coul</subfield>
    <subfield code="d">24 cm</subfield>
  </datafield>
  <datafield tag="300" ind1=" " ind2=" ">
    <subfield code="a">Contient un choix de textes de Diderot</subfield>
  </datafield>
  <datafield tag="300" ind1=" " ind2=" ">
    <subfield code="a">Bibliogr. p. 531-548</subfield>
  </datafield>
  <datafield tag="503" ind1=" " ind2=" ">
    <subfield code="a">Exposition</subfield>
    <subfield code="e">Diderot</subfield>
    <subfield code="f">Denis</subfield>
    <subfield code="j">1984-1985</subfield>
  </datafield>
  <datafield tag="517" ind1=" " ind2=" ">
    <subfield code="a">Diderot et l'art de Boucher à David</subfield>
  </datafield>
  <datafield tag="600" ind1=" " ind2="1">
    <subfield code="9">607757</subfield>
    <subfield code="3">026831406</subfield>
    <subfield code="a">Diderot</subfield>
    <subfield code="b">Denis</subfield>
    <subfield code="f">1713-1784</subfield>
  </datafield>
  <datafield tag="600" ind1=" " ind2="1">
    <subfield code="9">607757</subfield>
    <subfield code="3">026831406</subfield>
    <subfield code="a">Diderot</subfield>
    <subfield code="b">Denis</subfield>
    <subfield code="f">1713-1784</subfield>
  </datafield>
  <datafield tag="600" ind1=" " ind2="1">
    <subfield code="a">Diderot</subfield>
    <subfield code="b">Denis</subfield>
    <subfield code="f">1713-1784</subfield>
    <subfield code="x">Contribution in art criticism</subfield>
    <subfield code="x">Exhibitions</subfield>
  </datafield>
  <datafield tag="600" ind1=" " ind2="1">
    <subfield code="a">Diderot</subfield>
    <subfield code="b">Denis</subfield>
    <subfield code="f">1713-1784</subfield>
    <subfield code="x">Et l'art</subfield>
  </datafield>
  <datafield tag="601" ind1="0" ind2="2">
    <subfield code="a">Salon</subfield>
    <subfield code="c">Exhibition : Paris, France</subfield>
  </datafield>
  <datafield tag="604" ind1=" " ind2=" ">
    <subfield code="a">Diderot, Denis, 1713-1784</subfield>
    <subfield code="t">Les salons</subfield>
  </datafield>
  <datafield tag="606" ind1="1" ind2=" ">
    <subfield code="a">Art, Modern</subfield>
    <subfield code="z">17th-18th centuries</subfield>
    <subfield code="y">France</subfield>
    <subfield code="x">Exhibitions</subfield>
  </datafield>
  <datafield tag="606" ind1="1" ind2=" ">
    <subfield code="a">Salon (Exhibition)</subfield>
  </datafield>
  <datafield tag="606" ind1="1" ind2=" ">
    <subfield code="a">Art, French</subfield>
    <subfield code="x">Exhibitions</subfield>
  </datafield>
  <datafield tag="606" ind1="1" ind2=" ">
    <subfield code="a">Art</subfield>
    <subfield code="z">17e siècle</subfield>
    <subfield code="y">France</subfield>
    <subfield code="x">Expositions</subfield>
  </datafield>
  <datafield tag="606" ind1="1" ind2=" ">
    <subfield code="a">Art</subfield>
    <subfield code="y">France</subfield>
    <subfield code="y">France</subfield>
    <subfield code="y">Paris (France)</subfield>
    <subfield code="x">Expositions</subfield>
  </datafield>
  <datafield tag="675" ind1=" " ind2=" ">
    <subfield code="a">35</subfield>
    <subfield code="v">Éd. 1967</subfield>
  </datafield>
  <datafield tag="676" ind1=" " ind2=" ">
    <subfield code="a">350</subfield>
  </datafield>
  <datafield tag="680" ind1=" " ind2=" ">
    <subfield code="a">N6846</subfield>
    <subfield code="b">.D474 1984</subfield>
  </datafield>
  <datafield tag="701" ind1=" " ind2="1">
    <subfield code="9">607757</subfield>
    <subfield code="3">026831406</subfield>
    <subfield code="a">Diderot</subfield>
    <subfield code="b">Denis</subfield>
    <subfield code="f">1713-1784</subfield>
  </datafield>
  <datafield tag="712" ind1="0" ind2="2">
    <subfield code="a">Association française pour les célébrations nationales</subfield>
    <subfield code="c">(collab.)</subfield>
  </datafield>
  <datafield tag="712" ind1="0" ind2="2">
    <subfield code="a">Réunion des musées nationaux</subfield>
    <subfield code="c">(France</subfield>
    <subfield code="c">collab.)</subfield>
  </datafield>
  <datafield tag="712" ind1="0" ind2="1">
    <subfield code="a">France</subfield>
    <subfield code="b">Direction des monnaies et médailles</subfield>
    <subfield code="c">(collab.)</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">Abes</subfield>
    <subfield code="c">20020125</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="1">
    <subfield code="a">US</subfield>
    <subfield code="b">OCLC</subfield>
    <subfield code="c">20020125</subfield>
    <subfield code="g">AACR2</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="2">
    <subfield code="a">FR</subfield>
    <subfield code="b">AUROC</subfield>
    <subfield code="c">20020125</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">BN</subfield>
    <subfield code="c">20020125</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="c">20020125</subfield>
    <subfield code="g">AFNOR</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SU</subfield>
    <subfield code="d">20010705</subfield>
  </datafield>
  <datafield tag="801" ind1=" " ind2="3">
    <subfield code="a">FR</subfield>
    <subfield code="b">SF</subfield>
    <subfield code="c">19990110</subfield>
  </datafield>
  <datafield tag="915" ind1=" " ind2=" ">
    <subfield code="5">930662101:011133112</subfield>
    <subfield code="a">930662101a/</subfield>
  </datafield>
  <datafield tag="917" ind1=" " ind2=" ">
    <subfield code="5">930662101:011133112</subfield>
    <subfield code="a">abxx</subfield>
  </datafield>
  <datafield tag="930" ind1=" " ind2=" ">
    <subfield code="5">930662101:011133112</subfield>
    <subfield code="b">930662101</subfield>
    <subfield code="d">sa</subfield>
    <subfield code="a">75(44)"17" DID</subfield>
    <subfield code="j">i</subfield>
    <subfield code="2">CDU</subfield>
  </datafield>
  <datafield tag="991" ind1=" " ind2=" ">
    <subfield code="j">109617</subfield>
    <subfield code="y">025163111</subfield>
    <subfield code="q">75(44)"17" DID</subfield>
  </datafield>
  <datafield tag="999" ind1=" " ind2=" ">
    <subfield code="5">930662101:011133112</subfield>
    <subfield code="a">S 109617 S</subfield>
    <subfield code="b">930662101sa</subfield>
    <subfield code="d">1</subfield>
    <subfield code="e">1</subfield>
    <subfield code="1">cdu-pd</subfield>
    <subfield code="c">75(44)"17" DID</subfield>
    <subfield code="x">930662101a</subfield>
    <subfield code="z">jv-pd-l</subfield>
    <subfield code="5">09.07.1996/932101/jv4</subfield>
  </datafield>
</record>
