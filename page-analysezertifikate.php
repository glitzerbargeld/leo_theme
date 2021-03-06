<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<div id="primary" <?php astra_primary_class(); ?>>

    <?php
	$blueten_ids = get_posts( array(
		'post_type' => 'product',
		'numberposts' => -1,
		'post_status' => 'publish',
		'fields' => 'ids',
		'tax_query' => array(
		   array(
			  'taxonomy' => 'product_cat',
			  'field' => 'slug',
			  'terms' => 'cbd-blueten',
			  'operator' => 'IN',
		   )
		),
	 ) );

	 $oele_ids = get_posts( array(
		'post_type' => 'product',
		'numberposts' => -1,
		'post_status' => 'publish',
		'fields' => 'ids',
		'tax_query' => array(
		   array(
			  'taxonomy' => 'product_cat',
			  'field' => 'slug',
			  'terms' => 'cbd-oele',
			  'operator' => 'IN',
		   )
		),
	 ) );

	 $vape_ids = get_posts( array(
		'post_type' => 'product',
		'numberposts' => -1,
		'post_status' => 'publish',
		'fields' => 'ids',
		'tax_query' => array(
		   array(
			  'taxonomy' => 'product_cat',
			  'field' => 'slug',
			  'terms' => 'cbd-vape',
			  'operator' => 'IN',
		   )
		),
	 ) );

	?>

    <style>
    .az-button {
        text-decoration: none;
        text-align: center;
        width: 200px;
        display: block;
        background: transparent;
        border-radius: 15px;
        border: none;
        padding: 10px;
    }

    @media (hover: hover) {
        .az-button:hover {
            text-decoration: none !important;
            color: white;
            background: black;
        }
    }

    .az-wrapper {

        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 30px;
        width: 100%;
        padding: 5%;
        margin: 50px 0;
        text-align: center;

    }

    .table-wrapper {
        width: 100%;
        transition: 0.7s;
        max-height: 0;
        overflow: hidden;
    }

    .openTable {
        transition: 0.7s;
        max-height: 900px;
    }

    .az-wrapper a:hover {
        text-decoration: underline;
    }

    #az-oele {
        background-color: #fdebe0;
    }

    #az-blueten {
        background-color: #ebfbe5;
    }

    #az-vape {
        background-color: #e1f0ec;
    }

    #az-infos {
        padding: 0 5%;
        max-width: 1100px;
        margin: auto;
    }

    table {
        background: white;
        border-radius: 10px;
        margin-top: 20px;
    }

    tr:nth-child(1) {
        font-weight: bold;
    }

    #az-oele table,
    #az-oele table td,
    #az-oele table tr {
        border: 3px solid #fdebe0;
    }

    #az-blueten table,
    #az-blueten td,
    #az-blueten tr {
        border: 3px solid #ebfbe5;
    }

    #az-vape table,
    #az-vape td,
    #az-vape tr {
        border: 3px solid #E1F0EC;
    }
    </style>

    <script>
    jQuery(document).ready(function() {
        document.querySelectorAll(".az-button").forEach(element => element.addEventListener("click", () => {

            if (element.innerHTML === "Schlie??en") {
                element.innerHTML = "Zertifikate zeigen";
            } else {
                element.innerHTML = "Schlie??en";
            }
            document.getElementById(element.parentNode.id).querySelectorAll(".table-wrapper")[0].classList.toggle("openTable");
        }));
    });
    </script>

    <div style="max-width:1100px; margin: auto">
        <h1 style="text-align: center;">Aktuelle Analysezertifikate unserer CBD Produkte</h1>

        <p style="max-width: 1100px; margin: 20px auto; padding: 0 5%; text-align: justify; text-align-last: center;">
            SANALEO bietet ausgew??hlte Premium <b><a href="https://sanaleo.com/cbd-blueten">CBD Bl??ten</a></b>
            verschiedener Premium-Hersteller aus Mitteleuropa und
            hochwertige <b><a href="https://sanaleo.com/cbd-oele">CBD-??le</a></b> an. Wir garantieren <b><a
                    href="https://sanaleo.com/cbd-blueten">CBD Aromabl??ten</a></b> und <b><a
                    href="https://sanaleo.com/cbd-oele">CBD-??le</a></b> hervorragender Qualit??t. Unsere Sorten
            und ??le stammen, wie jedes andere unserer CBD Produkte, aus nachhaltiger und ??kologischer Landwirtschaft.
            Damit sind sie garantiert frei von Herbiziden, Pestiziden oder chemischen D??ngern.
            All unsere Produkte werden vor dem Verkauf von einem unabh??ngigen Labor auf ihre Inhaltsstoffe und die
            Einhaltung der rechtlichen Rahmenbedingungen untersucht und zertifiziert. Da SANALEO Transparenz wichtig
            ist, kannst Du jedes Zertifikat auf der jeweiligen Produktseite einsehen. Doch der Blick auf das Zertifikat
            kann das ungeschulte Auge ??berfordern: Deswegen haben wir <b><a style="text-decoration: underline;"
                    href="#az-infos">unten</a></b> f??r dich zusammengefasst, wie unsere
            Zertifikate zu lesen sind.</p>


        <div class="az-wrapper" id="az-oele">

            <img id="oel-logo" src="https://sanaleo.com/wp-content/uploads/2022/01/Icon_O%CC%88le.svg" alt=""
                width="50">
            <h3 style="font-weight: 200;">CBD-??le</h3>

            <a class="az-button">Zertifikate zeigen</a>


            <div class="table-wrapper">
                <table>
                    <tr>
                        <td>Produkt</td>
                        <td>CBD-Anteil</td>
                        <td>Zertifikat</td>
                        <?php
	foreach ( $oele_ids as $id ) {

	  $zertifikat_url = get_field("analysezertifikat_1", $id);
	  
	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_1", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

	  $zertifikat_url = get_field("analysezertifikat_2", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_2", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

	  $zertifikat_url = get_field("analysezertifikat_3", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_3", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

      $zertifikat_url = get_field("analysezertifikat_4", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_4", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

      $zertifikat_url = get_field("analysezertifikat_5", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_5", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

	}

	?>
                </table>
            </div>

        </div>

        <div class="az-wrapper" id="az-blueten">

            <img id="blueten-logo" src="https://sanaleo.com/wp-content/uploads/2022/01/Icon_Blu%CC%88ten.svg" alt=""
                width="50">
            <h3 style="font-weight: 200;">CBD-Bl??ten</h3>
            <a class="az-button">Zertifikate zeigen</a>
            <div class="table-wrapper">
                <table>
                    <tr>
                        <td>Produkt</td>
                        <td>Zertifikat</td>
                    </tr>
                    <?php
	foreach ( $blueten_ids as $id ) {
		$zertifikat_url = get_field("analysezertifikat_1", $id);
		if(!empty($zertifikat_url)) {
			echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a></td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
			unset($zertifikat_url);
		}
	}
	?>
                </table>
            </div>
        </div>

        <div class="az-wrapper" id="az-vape">

            <img id="vape-logo" src="https://sanaleo.com/wp-content/uploads/2022/01/Icon_Vape.svg" alt="" width="50">
            <h3 style="font-weight: 200;">CBD-Vape</h3>
            <a class="az-button">Zertifikate zeigen</a>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <td>Produkt</td>
                        <td>CBD-Anteil</td>
                        <td>Zertifikat</td>
                        <?php
	foreach ( $vape_ids as $id ) {

	  $zertifikat_url = get_field("analysezertifikat_1", $id);
	  
	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_1", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

	  $zertifikat_url = get_field("analysezertifikat_2", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_2", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

	  $zertifikat_url = get_field("analysezertifikat_3", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_3", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

      $zertifikat_url = get_field("analysezertifikat_4", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_4", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }

      $zertifikat_url = get_field("analysezertifikat_5", $id);

	  if(!empty($zertifikat_url)) {
		echo '<tr><td><a class="product-link" href="' . get_permalink( $id ) . '">'. get_the_title( $id ) . '</a></td><td>' . get_field("variante_5", $id) . '</td><td><a href="' . $zertifikat_url . '">Download</a></td></tr>';
		unset($zertifikat_url);
	  }
	}

	?>
                </table>
            </div>

        </div>

    </div>


    <div id="az-infos">


        <h2>Wie sind unsere Zertifikate aufgebaut?</h2>
        <h3>Bl??ten</h3>
        <p>Der erste Blick auf unser Bl??ten-Analysezertifikat l??sst eine Einteilung in vier Teile zu.
            Im Kopf des Dokuments stehen die Anschrift des Labors und unter der ??berschrift Details zu der
            analysierten
            Bl??ten-Probe. Dort siehst du Informationen wie u. a. die Bezeichnung der Bl??tensorte; die Lot- bzw.
            Batchnummer, aus der die Probe stammt; sowie den Auftraggeber SANALEO und die Proben-ID zur genauen
            Identifikation der Bl??tenprobe.
        </p>
        <p>Der Hauptteil des Zertifikats ist eine Tabelle mit allen Cannabinoiden, die in unseren Bl??ten vorkommen
            k??nnen. In der ersten Zeile unter den Benennungen ist zuerst das Gesamtgewicht der eingereichten Probe
            aufgef??hrt. Ab der zweiten Zeile werden die Cannabinoide aufgez??hlt. Diese sind sowohl als K??rzel als
            auch
            ausgeschrieben aufgef??hrt. In der Spalte ???Ergebnis??? steht, wie viel Milligramm (mg) der Substanz auf das
            Kilogramm (kg) in der Probe vorliegen. Steht in der Zelle ???ND???, so bedeutet das ???nicht detektierbar???:
            Der
            Messwert lag unter der Bestimmungsgrenze von 0,01 % bzw. 100 mg/kg. Dahinter, unter ???Einheit???, ist diese
            Menge nochmals in Prozent aufgef??hrt. Besonders relevant sind die Werte des Cannabidiols (CBD + CBDA),
            des
            Cannabigerols (CBG + CBGA) und des Tetrahydrocannabidiols (THC + THCA). Cannabidiol ist der Wirkstoff,
            um
            den es uns bei SANALEO CBD geht, denn CBD kann zahlreiche positive Wirkungen auf den menschlichen
            Organismus
            haben. Daher ist der Summenwert von Cannabidiol und Cannabidiols??ure fett gedruckt. Analog verh??lt es
            sich
            mit Cannabigerol. Auch fett gedruckt ist der Summenwert von Tetrahydrocannabidiol. Doch der Aussagewert
            liegt hier nicht vordergr??ndig in der therapeutischen Potenz des Wirkstoffes, sondern in seiner
            Zul??ssigkeit. Der Summenwert f??r Tetrahydrocannabidiol darf den Wert von 0,2 % nicht ??berschreiten. Da
            Tetrahydrocannabidiol berauschend wirkt und somit unter das Bet??ubungsmittelgesetz f??llt, w??re der
            Verkauf
            von Bl??ten mit einem h??heren THC-Gehalt als 0,2 % rechtswidrig. Abgesehen von diesen drei Cannabinoiden
            sind
            noch Cannabinol, Cannabichromen, Tetrahydrocannabivarin, Cannabidivarin und Cannabidivarin-Carboxyls??ure
            im
            Zertifikat aufgef??hrt. Diese wirken vorallem im Rahmen des Entourage-Effekts. Mehr dazu kann Du hier
            (https://sanaleo.com/der-entourage-effekt/)??nachlesen.
        </p>
        <p>Das Zertifikat zeigt unter der Tabelle ein Bild der analysierten Probe, des Datum des Probeneingangs und
            die
            Unterschrift des/der verantwortlichen Analytikers/Analytikerin.
        </p>
        <p>Abschlie??end stehen im Zertifikat noch eine Fu??note mit Lesehinweisen der Tabelle und der durchgef??hrten
            Analysemethode und G??tesiegel des Labors.
        </p>

        <h3>??le</h3>

        <p>Das ??le-Analysezertifikat besteht aus drei Seiten.
            Auf der ersten Seite stehen Referenzen des T??V-Labors zu dem jeweiligen Untersuchungsbericht. Dazu
            geh??rt u. a. die Probenbezeichnung, die Menge, das Datum des Untersuchungsbeginns und die Chargennummer.
        </p>
        <p>Der Hauptteil des Zertifikats auf Seite 2 ist eine Tabelle mit allen Cannabinoiden, die in unseren ??len
            vorkommen k??nnen. In der ersten Spalte werden die Cannabinoide aufgez??hlt. Diese sind sowohl als K??rzel
            als auch ausgeschrieben aufgef??hrt. In der Spalte ???Messwert??? steht, wie viel Milligramm (mg) der
            Substanz auf das Kilogramm (kg) in der Probe vorliegen. Die Deutung der Werte funktioniert wie unter (1)
            beschrieben.??
        </p>
        <p>Die dritte Seite des Zertifikats beinhaltet nur die Unterschrift der zust??ndigen Sachbearbeitung sowie
            nochmals den Auftraggeber und die Probenbezeichnung.
        </p>

        <p>Je nachdem, welche Cannabinoide f??r Dich besonders relevant sind, kannst du mithilfe des Zertifikats die
            perfekte Bl??te oder das perfekte ??l f??r Dich ausw??hlen.
        </p>
    </div>

</div>


</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>