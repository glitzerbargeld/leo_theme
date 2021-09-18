<style>
	.woocommerce div.product form.cart .variations {
	display: none;
	}
	.wp-post-image{
clip-path: url("#zuschnitt");
}
</style>


<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

//the_post();
//global $post;
global $product;



function svgmask($product) {
	//global $product;
	$mask_array = [
		"FS" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M778.18,825c0-9.5-.07-19,0-28.5.16-43.3.58-86.59.82-129.89q.54-99.21,1-198.4c.18-29.32.58-58.63.9-87.95q.45-41.22,1-82.44c.28-20.49.67-41,1.06-61.45.38-19.15.84-38.29,1.24-57.44.11-5.5.13-11,.26-16.5.18-7.48.48-15,.61-22.45a17.54,17.54,0,0,0-2.86-10.44c-1-1.51-2.16-2.79-2.19-4.84,0-1.88-1.5-2.93-3.27-3.28-4.24-.84-8.49-1.68-12.76-2.36C753,117.33,742,117.87,731,118c-1,0-2.75,0-2.84.31-.62,2.33-1.46,1.34-2.83.65a11.72,11.72,0,0,0-5.08-1.28c-11.49,0-23,.21-34.46.37s-23.21,1-34.75.36c-8.34-.46-16.64.14-25-.17-9.12-.35-18.28.67-27.43.75q-24.48.19-49-.06c-13.78-.13-27.55-.7-41.32-.73-11.26,0-22.51.33-33.76.77a66.42,66.42,0,0,0-11.8,1.67c-6.32,1.41-12.61,3-18.82,4.87-3.49,1-6.8,2.67-10.2,4a5.55,5.55,0,0,0-3.49,4.54,49.81,49.81,0,0,0-.88,8.42c0,8.95.3,17.91.41,26.86q.46,39,.86,78c.07,6.67,0,13.34.09,20q.48,37.2,1,74.39c0,1.94,0,3.88,0,6.26-3.73.15-7,.93-9.87.25-5.75-1.35-11.45-1.07-17.46-.75-1.37-4-3.13-8-4.07-12.22a159.84,159.84,0,0,1-4.36-34.06c-.06-10-.66-20-1.23-29.95-.75-13.29-1.89-26.56-2.49-39.85a49.47,49.47,0,0,0-6.93-24.12c-9.3-15.35-22.07-25.56-40.21-27.92-8.28-1.08-16.66-1.06-24.72,1.63a52,52,0,0,0-28.78,22.74c-3.74,6.11-7.47,12.53-7.71,19.79-.41,12.31-.26,24.67.23,37s2,24.54,2.46,36.83c.46,13,.21,26,.17,39a29.35,29.35,0,0,1-2.31,11.86c-17.71,1.92-35.18,3.48-52.41,9.38-.08,6.52-.2,12.8-.22,19.07q-.06,20.46,0,40.92c0,7.5.19,15,.27,22.49.18,14.95.39,29.9.48,44.85,0,3.65-.38,7.3-.55,10.95-.21,4.73,1.55,9.58-1.05,14.18-.42.74,0,2,0,3q.41,12.47.8,24.93c.14,4.52,1,5.68,5.45,7.09,2.5.8,5,1.7,7.49,2.57,0,8.25,2.74,16.1-.25,24.52l7.73,4.25a69.31,69.31,0,0,1-4.68,5.55,78.3,78.3,0,0,0-14.13,17.94c-1.74,3.16-4,6.33-4.62,9.75-1.61,9.16-2.71,18.46-1.86,27.79.29,3.19-1.93,6.55.63,9.58,0,0-.15.26-.24.42l-2.4-.81c-.08,1.18-.17,2-.17,2.8-.19,24.48.53,49-.54,73.43-.67,15.59.23,31.24.33,46.86,0,3.31-.55,6.63-.52,9.94q.15,22.75.43,45.5H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"BS" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M778.55,825l0-10.18L779,707.73l.5-145.54.74-103.27.24-101.18.87-108.45.86-72.09s.74-37.59-.25-47.57-13.06-12.7-13.06-12.7c9.36.13,10.84-2.09,10-3.45-3.1-4.87-43-3.7-43-3.7l-95,1.36-94.53-.25-87.25-.61s-29.82,12-32.78,12.81a8.11,8.11,0,0,0-4.37,3.19l.13,32.21,1.67,66.93,1,65,.14,57.62c-10.41-2.08-27.77-3.06-27.77-3.06-4.86-5.27-5.14-47.9-5.14-47.9l-2.08-75.12s-4.72-42.07-54.57-43.87c-33.5,1.62-47,23.24-52.18,36.6a45.55,45.55,0,0,0-3,16.38c0,13.68,0,47.76-.19,67.7a206.4,206.4,0,0,1-6,46.88c-42.23.3-43.82,8.51-43.82,8.51L229,402.6l-1.09,50.45-1,50.64-.69.4a4.23,4.23,0,0,0-2.48,2.18v30.86c1.49,4.15,11.48,7.12,11.48,7.12s1.09,20.77,1.09,24.13,7.51,7.42,7.51,7.42c-18.39,15.53-23.34,32.45-23.34,32.45v17.6a8,8,0,0,0-4.06,1.68c-.09,6.73,1,29.39,1,29.39l2,40.09,1.55,77.45V825H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"ED" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M756.55,824c.09-3.23.19-6.46.27-9.69.5-21.15.78-42.31,1.12-63.46.33-20.48.58-41,.94-61.45.32-17.82.73-35.64,1.13-53.45.27-12.15.61-24.3.88-36.46.35-16,.66-32,1-48q.47-21,1-42,.58-23.46,1.22-46.94.4-16,.78-31.95.51-21.74,1-43.46c.34-15.15.6-30.3,1-45.45.58-20.63,1.3-41.27,2-61.9q.55-17.73,1-35.45.72-26.72,1.37-53.45c0-.83,0-1.66.07-2.49.54-16.3,1.21-32.6,1.55-48.91.11-5.52-1.41-10.63-7.22-13,1.54-5.55,1-6.17-4.05-8.35A55.5,55.5,0,0,0,739.74,114c-34.65-.1-69.3,0-104,.08q-65.79.06-131.57.09c-10.12,0-20.25.2-30.35-.21a44.27,44.27,0,0,0-22.57,5.2c-2.73,1.4-4.66,3-4.87,6.31-.07,1-1,2-1.34,3a16,16,0,0,0-1.42,4.68c-.18,5.79-.09,11.59-.1,17.39,0,2.64.09,5.29,0,7.93-.47,14.27-1.21,28.54-1.42,42.81-.28,19.5-.13,39-.23,58.49q-.21,40.46-.54,80.89a38.87,38.87,0,0,1-.74,5.13l-24.56-2.38c-4.29-10.2-6.34-21-7.72-31.84-1.68-13.18-2.41-26.5-3-39.79-.73-15.13-.79-30.3-1.33-45.44-.4-11.22-2.25-22.13-7.75-32.18-10.42-19.08-29.75-28.92-50.89-28.06a51.71,51.71,0,0,0-26.81,8.46A59.59,59.59,0,0,0,298,196.69c-4.7,8.95-6.1,18.7-6,28.54.18,15.8,1.29,31.59,1.56,47.39q.45,26.24,0,52.48c-.09,5.74-1.42,11.46-2.21,17.42-13.67.11-26.52,3.68-39.48,6.52-5.21,1.15-6.33,2.45-6.73,7.75-.31,4.15-.28,8.33-.33,12.49-.31,23.48-.52,47-.92,70.44q-.45,27-1.3,53.95c-.11,3.25-1.21,6.47-1.81,9.71a34,34,0,0,0-.83,5.34c-.17,8.33-.15,16.66-.34,25-.06,2.28.69,3.95,2.64,4.93,3.24,1.63,6.56,3.1,10.21,4.81v21c2.29,3.19,6.57,3,9.42,4.53-5.8,6.71-11.59,13.1-17,19.8a34,34,0,0,0-5.05,9.11c-2.87,7.52-3.68,15.44-3.88,23.45,0,2-.32,4.49-1.47,6-1.65,2.2-1.6,4.41-1.6,6.79q0,52.46,0,104.91,0,42.47,0,84.94H-1V999H999V824Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"HD" =>	'<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M784.2,825c-.35-26.51-.67-53-.8-79.52-.18-36.33-.14-72.66-.14-109q0-75.72,0-151.44c0-37.5-.25-75-.15-112.49q.23-83.24.83-166.46c.11-16.31.68-32.62,1-48.94.17-10.16.33-20.32.23-30.48a32,32,0,0,0-1.56-8.28c-1.06-3.73-3.08-6.65-7.46-7a14.69,14.69,0,0,1-2.89-1c2.16-1.89,5.23.21,6.63-2.73-.06-1.83-1.34-2.61-3.12-3-5.82-1.35-11.57-3.17-17.46-4a165.76,165.76,0,0,0-23.81-2.14c-16.81,0-33.61.83-50.42,1.18q-30.44.63-60.88,1-40.23.54-80.46.89c-25,.21-50,.22-75,.42a34.66,34.66,0,0,0-8.37,1.06q-10.14,2.63-20.14,5.74c-7.13,2.25-14.17,4.81-21.26,7.19-3.45,1.16-5.05,3.67-5.28,7.13s-.53,7-.44,10.45c.43,15.8,1.08,31.6,1.48,47.4.44,17.15.72,34.3,1,51.45.36,22,.75,43.95,1,65.92.15,14.61,0,29.22,0,43.54l-27.13-1.28c-.73-2.94-2-6.26-2.34-9.68-1.49-14.89-3.2-29.78-3.91-44.71-.87-18.3-1.13-36.64-1-55a63.88,63.88,0,0,0-7-29.75c-5.4-10.69-13.95-18.15-24.43-23.38-10.25-5.11-21.14-6.22-32.54-4.52-24.77,3.71-41.65,20.88-46.44,44.53-1.37,6.76-1.21,13.9-1.16,20.87.11,15.15.7,30.29.89,45.44.08,6-.35,12-.61,17.95-.38,9-.73,18-1.27,26.93-.22,3.61-.91,7.2-1.4,10.94a24.65,24.65,0,0,1-2.65.35,200.55,200.55,0,0,0-33.16,3.56,65.2,65.2,0,0,0-7.24,1.87,4.56,4.56,0,0,0-3.39,3.93,77.34,77.34,0,0,0-1,8.4c-.41,13.82-.81,27.64-1,41.46-.39,31.15-.65,62.3-1,93.45,0,1.5,0,3-.15,4.49-.05.82,0,1.89-.44,2.41-2.67,3-1.84,6.45-1.79,9.9.09,7.31,0,14.63-.1,21.94-.06,3.07,1,4.88,4.07,6,2.63,1,5.28,1.86,8.44,3V572.9l6.59,4.18c-7.68,7.28-14.61,14.94-19,24.78s-3.36,20.5-3.95,30.84l-3.7,2.36c0,1.63,0,3.29,0,4.94.22,10.82.53,21.64.68,32.47.1,7.47-.12,14.95,0,22.42.23,10.65.76,21.3,1,31.94.4,17.31.77,34.61,1,51.91.16,13.15-.11,26.29.05,39.43l.09,6.83H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"small_ml" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M904.45,825c-.05-8.82-.09-18.6,0-23.63.15-14.54,1.16-58.46,1.16-58.46,0-25.27,3.8-116.8,3.8-116.8a2.74,2.74,0,0,0-1.65-2.68V597.09a13.43,13.43,0,0,0-4.46-9.49c10.9-4.05,14.94-9.71,14.94-9.71V574.3c0-3.38-.82-4.62-.82-4.62V530c-3.55-7-14.33-9.41-14.33-9.41V413.83a5.9,5.9,0,0,0-3.38-5.28,12.06,12.06,0,0,0-.38-4.83c-4.95-9.58-31.43-9-31.43-9l-1.1-15.41-3.63-4.3-4.52-75.41s-.33-29.84-1.1-33.8c-1.54-10.57-15.63-32.7-41.28-32.7-34.35,0-41.4,36.44-41.4,36.44l-7.19,105.1-2.07,4.71-2.24,14.82c-31.27,0-32.92,7.65-32.92,7.65v2.53c-3.13,0-3.52,2.37-3.52,2.37l-1.65,111.38c-15,1.82-15.11,8.84-15.11,8.84l-.41,42.6v5.62c2.22,4.62,11.14,10.65,11.14,10.65-3,0-3.71,3.88-3.71,3.88v30.8a2.83,2.83,0,0,0-1.65,3.22L714.89,825H624.48L626,726.23s1.24-87.44,1.24-90.66-2.15-4.05-2.15-4.05V592.74c0-6.16-3.47-9-3.47-9,14.54-4.79,16.85-12.22,17.34-13.22s.25-4.12.25-6.44-1.32-2.31-1.32-2.31l-.66-50.57c-3.3-10.4-17.34-13-17.34-13l-.08-136.32a8.86,8.86,0,0,0-4-4.87l-.24-4.7c-8.59-12.22-40.38-10.49-40.38-10.49s-.49-15.36-1.24-19.07a24.55,24.55,0,0,0-3.3-7.52L566.21,184s-9.91-46.32-52.1-46.32c-54.66,0-57.05,54-57.05,54L450.78,314l-4.29,3.72-1.66,22.46s-19.73-.08-29.47,2.31-9.7,10.49-9.7,10.49c-4.22,0-5.36,2.78-5.36,2.78L397.55,495.2c-17.17,4.19-20.31,12.94-20.31,12.94L376.14,566c1.32,5.37,12.8,12.39,12.8,12.39-2.73,1.57-2.73,5.2-2.73,5.2v45a2.27,2.27,0,0,0-2.42,1.65L385.44,825H284.8c1.17-53.78,4.14-189.51,4.14-192.16a5.1,5.1,0,0,0-2.31-4.54s.5-25.35,0-30.8-3.38-7.84-3.38-7.84c12.88-5.2,15.52-10.57,15.52-10.57s.08-3.88.08-5.7a6.65,6.65,0,0,0-1-3.3s-.74-33.36-.74-36.16a13.8,13.8,0,0,0-.58-4.38c-3.47-6.11-13.87-7.93-13.87-7.93V411.2c0-2.37-3.3-3.8-3.3-3.8l-.61-4.29c-6.66-8.09-31.31-7.4-31.31-7.4l-.57-16.1-3.35-4.3s-4.79-93-5.28-107-15.69-28.9-29.56-33.19a41.68,41.68,0,0,0-27.75,1.82c-13.1,5.54-26.25,18.37-26.25,38.64,0,37.15-6.48,98.38-6.48,98.38l-2.85,4.29-1.9,17c-16.29,0-22.57,1.1-27.52,3s-5,7.38-5,7.38a3.7,3.7,0,0,0-3.08,1.87l-3.63,111.63c-11.7.77-16,8-16,8S87.23,567,87.23,571.58s.58,5.2.58,5.2c4,7.27,12.88,10.07,12.88,10.07-4.21,1.65-5,6.2-5,6.2v31.87c-1.4.08-1.7,1.73-1.7,1.73,1.17,16,0,150.2-.49,198.35H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"FSPack" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M930.2,825c.17-8.43.52-16.85.7-25.28.12-5.22,0-10.44,0-16.09s-.62-6.58-.17-10.21c.56-4.48,1-9.21,1.15-13.86.44-11.45,0-22.91,1-34.39,1.25-15.58,1.49-31.24,2-46.87.42-11.48.87-23,1-34.45q.21-34.49,2-68.9c.61-11.63.7-23.29,1-34.94.71-26,1.5-51.93,2.1-77.9.54-23.31.88-46.63,1.26-69.94.1-6.13,0-12.27,0-18.6l-5.07-6c-5.4-.77-11.44-1.85-17.53-2.45-6.78-.66-13.6-.85-20.4-1.32-8-.56-15.89-1.68-23.84-1.72-27.13-.11-54.28-1-81.4.9-4.3.31-8.62.51-12.91.91-4.09.38-8.16,1.16-12.25,1.36-6.45.32-12.92.22-19.37.46-3.32.13-6.63.57-9.94.94-2.09.23-3.67,1.2-4.27,3-3.3,2.49-2.92,7.93-2.9,8.59a125.22,125.22,0,0,1-.33,13.27c-.66,8.44-.81,16.92-1,25.39-.52,29.14-.93,58.29-1.45,87.43a53.47,53.47,0,0,1-.7,6.19l-8.1,3.41c-.86-4.12-1.79-8.3-2.59-12.51a25.91,25.91,0,0,1-.69-5.91c.39-8.81,1-17.6,1.42-26.41.48-10.47.7-21,1.24-31.44a33.14,33.14,0,0,0-5.8-20.25c-9.74-15-29.12-19.36-44.18-12.57-8.45,3.81-14.33,10.29-17.7,19.31-2.83,7.57-2.63,15.39-3.24,23.11-.91,11.44-.87,23-1.38,34.43a145.84,145.84,0,0,1-1.32,14.91c-.51,3.38-1.8,6.64-2.68,9.77-10.42,1.17-20.66-1.32-30.43,2.54a16.78,16.78,0,0,0-.68,2.47c-1,9.59-2.08,19.17-2.87,28.78-1.2,14.58-2.13,29.18-3.28,43.76-.47,6-1.23,11.91-1.79,17.87q-1,10.41-1.8,20.83a27.35,27.35,0,0,0,.27,4l7.16,3.41v16.69l7,3.93c-3,3.33-6.16,6.54-9.06,9.93A35.86,35.86,0,0,0,601.11,673c-2,6.2-1.36,12.7-3.1,19-1.59,5.79-.87,12.22-1.17,18.37-.05,1.17-.12,2.33-.18,3.5-5.62-13.53-10.43-27.14-15.81-40.53-2.38-5.94-3.29-11.74-2.06-17.95a11.09,11.09,0,0,0,.08-2c.35-14.48.74-29,1-43.45.37-18.32.64-36.64,1-54.95s.77-36.64,1.11-55a26.25,26.25,0,0,0-.54-4.9c-.33-1.91-1.1-3.78-1.16-5.69a30.39,30.39,0,0,0-7-18.8c-3.07-3.72-6.12-7.47-9.11-11.25a20.18,20.18,0,0,1-1.59-2.84l9.14-1.91c.22-5.14.44-10.17.63-14.67l6.88-2c0-6.16,0-11.47,0-16.79-.09-7.65-.24-15.3-.38-22.95-.17-9.13-.53-18.26-.48-27.39.07-13.49.51-27,.61-40.46a90.41,90.41,0,0,0-.93-9.95c-9.74-3.94-19.49-3.25-29.08-3.73-2.15-6.88-3.88-13.41-3.75-20.33.37-19.49.61-39,1-58.46.23-12.09-5.42-21.39-14.81-27.94-11.19-7.8-23.78-8.76-36-2.45s-19.3,16.55-19.57,30.78c-.27,14.65-.62,29.31-.71,44a132,132,0,0,1-2.44,25.79c-.52,2.53-1.26,5-1.83,7.24L441.48,317c-.56,4.07-1.34,7.78-1.51,11.52-.48,10.48-.68,21-1,31.45-.43,12.15-.85,24.3-1.39,36.44-.52,11.79-1.2,23.57-1.76,35.35a20.65,20.65,0,0,0,.4,3.46l6.85,2.46v13.9l9.09,2.23a19.36,19.36,0,0,1-1.84,3.09c-2.34,2.59-4.84,5-7.19,7.61-4.75,5.2-8.77,10.88-10.55,17.8-.73,2.84-1.35,5.82.37,8.62l-2.81-.35c-.12,1-.25,1.62-.26,2.27q-.52,33.45-1,66.9-.55,40.47-1,81a34,34,0,0,1-.39,3.63l-8.89,2.06c-3.48,8.1-4.38,16.38-5.4,24.62-1.26,10.22-2.33,20.47-3.49,30.71a31.5,31.5,0,0,1-.48-6c0-1.31.17-2.93-.49-3.88-1.58-2.29-1.42-4.73-1.33-7.2a33.76,33.76,0,0,0-9.16-25.07c-3-3.2-5.75-6.57-8.62-9.87l8.51-3.44V633.43l7.67-3.93c-.51-9.23-1.2-17.84-1.42-26.46-.54-21.48-.84-43-1.3-64.44-.16-7.49-.46-15-.77-22.46-.14-3.51-.84-4.54-4.28-5-7.05-1-14.14-1.64-21.23-2.39-1.61-.17-3.23-.19-5.19-.29-.69-4.69-1.46-9.09-1.94-13.52a86.93,86.93,0,0,1-.32-9q-.07-26.24-.07-52.49c0-9.71-3.75-17.82-10.55-24.52-7.86-7.75-17.5-10.16-28.37-9.32-19.6,1.51-31.33,18.53-31.46,34-.17,20-.58,40-.9,59.95A35.21,35.21,0,0,1,295,507.37l-19.53,3.4c-.17-1.51-.39-2.64-.4-3.77q-.37-23.72-.7-47.42c-.37-28.48-.77-57-1-85.45,0-5.12-2-8.92-5.56-11.59-1.27-.86-1.83-1-2.56-1.49a24.93,24.93,0,0,0-4.45-4.74c-4.37-4.17-9.36-6.17-15.46-5a28.08,28.08,0,0,1-4.41.13c-2.22.07-4.45.18-7.68.32a2.06,2.06,0,0,0-1.29-.51c-7.79,0-15.61-1.12-23.37.84-2.13.54-4.53.09-6.8,0-.79,0-1.58-.4-2.37-.4-4.33,0-8.66,0-13,.2-12.25.56-24.51-.31-36.79,1.09-9,1-18.21.34-27.33.44-16,.17-31.95.24-47.92.63-4.1.1-8.21,1.13-12.25,2-1.47.32-2.75,1.43-4.05,2.15.63,1.63,1,2.63,1.5,3.89-4.74,1-6.07,4.07-5.76,8.21.08,1,0,2,0,3,.33,9,.73,17.92,1,26.88.15,5,0,10,.09,15,.09,10.16,0,20.33.33,30.49.4,13,1.27,25.91,1.65,38.88.41,13.81.43,27.63.73,41.45q.49,22.19,1.22,44.37c.62,19.3,1.39,38.59,2,57.88.41,13.14.68,26.29,1,39.43.2,8,.34,16,.64,24,.08,2.09.77,4.16,1,6.26.26,2.68.33,5.39.52,8.67a49.92,49.92,0,0,0,.54,23c-.17,2.53-.46,5.33-.51,8.13-.07,4.33,0,8.66,0,13q.48,28,1,55.92c.09,4.59.6,9.17.64,13.76,0,1.53,0,3.05-.13,4.58H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"BSPack" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M926.22,825l12.09-262.76s11.62-189.08,11.62-193-5.12-5.12-5.12-5.12,3-.72-1.87-3.8-93.36-9-93.36-9c-37-.66-99.08,4.19-108.55,5.73s-11,9.25-11,9.25l-2.86,140.58c-4-1.87-22.68-2.31-22.68-2.31-3.31-16.4-.39-80.26-.39-80.26-1.32-12.88-14.48-31.43-33.8-30.38C638.86,396.22,635,422.53,635,422.53s-1.32,29.73-2,49.76S627.92,503,627.92,503c-27.74-.46-31.5,3.71-31.5,3.71L593,600.88l-.83.74c-1.07.09-1.9,1.49-1.9,1.49L590,623.46c2,2.69,7.94,4.54,7.94,4.54v16.81a10.61,10.61,0,0,0,6.11,2.86c-13.43,9.19-15.49,19.27-15.49,22.57v4.09c-3.1,0-4.58,5.07-4.58,5.07v27.21l0,4.75S569,645,566.7,642s-4.63-3.3-4.63-3.3v-143a3.12,3.12,0,0,0-1.76-2.92V483.3c0-11.39-17.6-27.08-17.6-27.08a15.34,15.34,0,0,0,8.38-2.6l0-15.23c6.71,0,7.38-1,7.38-1V416.81l-.67-1.1-.88-84s.75-11.23-1.23-12.72c-3.8-3.14-29.89-2.39-29.89-2.39s-3.39-11.23-4.13-17.67-1.65-64.41-1.65-64.41-2.89-31.7-34.44-31.7-34.67,30.38-34.67,30.38v45.66c0,25.19-2.87,37.27-2.87,37.27s-20.64.16-25.65.16-5.28,6.44-5.28,6.44l-.88,42.44-.83,50.72v19.73c0,1.77,6.5,3.3,6.5,3.3v12.9c0,2.45,9.11,3.24,9.11,3.24l.13,1.16C416.31,466.46,413,480.83,413,480.83v11.56c-2.89,0-2.89,3.3-2.89,3.3l.91,143.05L393.85,705.8V683.23c0-2.26-1.65-2.48-1.65-2.48S392,674,392,663.85s-16-22.79-16-22.79v-1.15a9.39,9.39,0,0,0,6.77-2.92V622.57s7.76-1.49,7.76-3.85V601.16c0-3-1.54-4.13-1.54-4.13l-1.07-22.57V504.14c-5-6.5-28.74-5.23-28.74-5.23-7.51-11.15-6.19-73.65-6.19-81.58s-9.91-31.46-36.74-31.46S283,412.13,283,421.87V463.4c0,30.22-3.08,33.64-3.08,33.64l-16.76,0-.14-133c-3.14-1.9-1.27-3.33-3-6s-22.63-9.25-22.63-9.25l-87,3.14-50,.88s-31.59,0-41.34.77-9.19,6.61-9.19,6.61l1.1,58.4,2.2,169.1L55.76,825H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"HDPack" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M932,825c0-15.89.1-31.78-.88-47.66-.19-3.11.77-6.26.84-9.41.38-17.31.66-34.62,1-51.94.33-18.82.79-37.64,1-56.46.4-42.65.63-85.3,1-127.95q.35-38,1-76c.36-24.15.73-48.3,1.27-72.44.13-6.22-.53-11.91-6.68-16.41-14.41-.86-29.66-2-44.93-2.63s-30.62-.87-45.93-1.25c-9.12-.22-18.26-.81-27.36-.5-13.44.45-26.88,1.37-40.29,2.44-9.1.73-18.17,1.94-27.22,3.15-4.79.64-8.28,4-8.76,8.29a122,122,0,0,0-.77,13.92c0,7.46.6,14.92.56,22.39-.15,32-.44,64-.7,95.95,0,1.13-.15,2.25-.22,3.19l-17.5-2.62a11.42,11.42,0,0,1-.92-2.59c-.79-6.43-2-12.86-2.12-19.31-.31-16.33,0-32.66-.16-49A35.85,35.85,0,0,0,703,412.34c-4.14-4.11-9.63-5.73-15.08-7.36-13.87-4.15-28.44,2.33-35.78,11.79a38.55,38.55,0,0,0-8.16,23.62c-.17,11.33.11,22.67-.2,34-.21,7.8-1.05,15.58-1.72,23.36-.28,3.26-.83,6.49-1.2,9.26-8.6,1.05-16.49,2-24.38,3-4.33.5-5.21,1.3-5.53,5.46-.3,4-.66,8-.82,11.94-.78,19.3-1.47,38.6-2.24,57.89-.25,6.32-.64,12.64-1,19a17.47,17.47,0,0,1-.66,2.89,27.08,27.08,0,0,0-1.18,5.22c-.16,5.3-.06,10.61-.06,16.64l7.79,3.38c-.25,6.49-.51,13.2-.77,19.81l4.21,3.16c-5,4.7-10.59,8.87-12.8,15.21s-3,12.88-4.59,19.9c-2.08.47-1.9,2.83-1.93,5-.06,5.06,0,10.13,0,15.19a48,48,0,0,1-3.72-10.32q-5.15-19.81-10.2-39.64c-1.12-4.41-2.55-8.64-6.66-11.43,0-1.25,0-2.41,0-3.57-.2-19.64-.45-39.29-.59-58.93-.21-27.15-.31-54.31-.52-81.46,0-5.07,1.07-10.32-1.92-15-1.05-8.81-5.84-15.34-12.41-20.93-2.47-2.1-4.49-4.74-7.08-7.51l9.9-2.8V438.7l8.54-2.29c0-5.9,0-11.22,0-16.54a12.81,12.81,0,0,0-.22-3c-2.87-11.55-3-23.42-3.55-35.17-.7-16.14-.34-32.33-.54-48.49-.05-4.47-.49-8.93-.76-13.59-4.8-2.57-10-2.87-15-3.33s-10.24-.39-15.79-.57c-.7-4.31-1.76-8.36-2-12.45-.54-11.14-.79-22.3-1-33.46-.15-7.5,0-15-.12-22.5A40.14,40.14,0,0,0,528,228.28c-5-9.1-12.85-13.92-22.79-16.27-17-4-33.52,6.08-39.48,19.86-2.67,6.19-2.9,12.58-2.9,19.07,0,18.83,0,37.67-.08,56.5a73.51,73.51,0,0,1-1.06,8.67c-2.1.23-3.87.48-5.65.59-7.29.47-14.59.83-21.87,1.36-3.69.27-4.12.83-4.42,4.62-.28,3.48-.67,7-.69,10.45-.14,25.5-.18,51-.32,76.49,0,2.47-.68,4.93-.72,7.4-.1,6.62,0,13.24,0,20.12l6.75,1.62V460l9.85,2.61c-1.46,3-3.79,4.8-6.08,6.72-5.58,4.7-9.9,10.27-11.38,17.63-.3,1.47-.43,3-.78,4.42-.26,1.11-.42,2.63-1.18,3.17-2.52,1.77-2.32,4.24-2.31,6.73q0,20,.09,40c0,5.67,0,11.34.26,17,.42,10.14,1.34,20.27,1.49,30.41.27,17.67.11,35.33.13,53v5.21c-5.35,2.23-5.33,2.23-5.86,7.81a30.93,30.93,0,0,1-.54,3.45c-1.81,9-3.61,17.91-5.49,26.86-1.91,9.1-4,18.18-5.83,27.3a57,57,0,0,0-1.3,9.88c-.36,14.31-.54,28.63-.77,42.95q-.48,29.91-.92,59.84h-.59q-.06-6.86-.05-13.72c0-34.5,0-69-.19-103.49,0-10.13-.63-20.25-1-30.38a11.66,11.66,0,0,0-.54-2.92c-2-6.46-4.84-12.4-9.76-17.17-2.25-2.18-4.55-4.32-6.92-6.56.79-.63,1.13-1.07,1.57-1.22,5.55-1.92,5.48-1.92,5.67-7.63.12-3.62.49-7.23.72-10.55l5.9-2.72c.06-1.27.18-2.26.14-3.24-.25-7.46-.63-14.93-.78-22.4-.41-20.81-.7-41.64-1.1-62.46-.15-8-.26-16-.8-23.95-.39-5.83-.87-6.5-6.67-7.48-6.7-1.14-13.53-1.47-20.3-2.12-1.59-.16-3.18-.21-5.17-.34-.68-8.6-1.71-16.7-1.87-24.81-.31-16,.15-32-.26-48-.36-14-7.31-24.68-19.75-30.71-14.23-6.89-31.66-4.45-42.8,9.53-5,6.24-8.19,13.2-7.85,21.55.3,7.49.18,15,.06,22.5q-.36,21.72-1,43.45a58.29,58.29,0,0,1-1.25,7.65c-8.53-.85-16.53,2.43-25.3.73,0-1.91,0-3.71,0-5.5q-.42-55-.85-109.94c0-5.67.26-11.36-.21-17s-2-11.09-6.36-15.2c-3.76-3.51-8.42-4.7-13.32-4.77s-9.64.42-14.47.53c-16.48.39-33,.81-49.45,1s-33,.33-49.49.33c-18.33,0-36.66-.13-55-.29-3.45,0-6.9-.48-10.36-.74-.7,1.37-1,2.88-1.78,3.3-4.25,2.19-4.31,6.1-4.24,9.93.25,13.47.68,26.93,1,40.39.4,16.82.8,33.63,1.09,50.45.22,12.66.1,25.34.46,38,.33,11.3,1.24,22.59,1.65,33.89.43,11.81.54,23.63.83,35.45q.52,20.48,1.13,40.94c.28,9.82.63,19.63.89,29.45q.6,22.22,1.11,44.43.54,23,1,45.93c.37,17.64.75,35.28,1,52.92.15,9.33,0,18.67.16,28,.08,5,.31,9.95.5,14.93H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"EDPack" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M921.25,825v-.09c0-4.33.34-8.64.54-13,.64-13.94-.6-28,2.05-41.8a9.33,9.33,0,0,0,0-1.5c.19-16,.65-31.88,1.89-47.82,1.44-18.41,1.65-36.91,2.35-55.37.13-3.5,0-7,.15-10.5.56-12.29,1.2-24.58,1.8-36.88.07-1.49.09-3,.18-4.49.54-9.13,1.24-18.24,1.6-27.38.47-12.11.58-24.23,1-36.34.59-16.63,1.41-33.26,2-49.89.52-15.14.84-30.29,1.25-45.43,0-1.67.11-3.33.24-5,.51-6.45,1.51-12.91,1.49-19.36a284.1,284.1,0,0,1,3.92-47.67c.47-2.86.07-5.87.07-9l-6.22-2.08c1.84-4.39,1.38-5.11-2.88-5.5-6.29-.57-12.56-1.38-18.86-1.88-13.59-1.06-27.18-2.17-40.79-2.92-10.79-.61-21.61-1-32.42-1-14,0-27.95.58-41.93.91-.66,0-1.32.13-2,.18-3.31.25-6.63.45-9.93.76s-6.49.72-9.74,1.06c-8.42.88-16.83,1.94-25.26,2.55-4.35.31-7.94,2.07-12.06,4.3,0,3.87.08,7.85,0,11.83-.28,11.62-.52,23.23-1,34.84-.45,11.14-1.39,22.26-1.73,33.39-.57,18.48-.82,37-1.23,55.45a30.34,30.34,0,0,1-.49,3.43c-6.48-2.47-13.46.46-19.65-3.69a33.38,33.38,0,0,1-1.89-5.27c-1.26-6.5-2.78-13-3.32-19.58-.79-9.77-.9-19.6-1.16-29.41-.17-6-.08-12-.09-18a40.5,40.5,0,0,0-3.77-17.92C700.24,404.69,692,397.53,679.92,396c-6.52-.86-13.16-.71-19.15,1.81A38.13,38.13,0,0,0,642.41,414c-2.31,4-4,8.19-3.72,13.2.42,7.13,0,14.32-.07,21.49-.08,5-.78,10-.39,14.91,1,13.16.34,26.28-.42,39.41a13.28,13.28,0,0,1-.54,2.35c-6.76,1.08-13.31,2-19.82,3.21-3.43.62-6.77,1.67-9.33,4.39-.37,6.78-.87,13.57-1.09,20.38-.33,10.15-.35,20.3-.69,30.45-.44,13.14-1.06,26.27-1.63,39.41-.05,1.15-.34,2.3-.46,3.45-.41,4-.94,7.9-1.16,11.87-.2,3.47,0,7,0,11.38l7.52,4.26v13.8l5.2,3.69c-1.14,1.2-2.14,2.09-2.94,3.13-3.13,4.07-6.36,8.09-9.25,12.33-4.44,6.52-3.83,14.48-5.21,21.74-1.44,7.54-.51,15.52-.64,23.31,0,1.1-.16,2.19-.32,4.28-1-1.43-1.69-2-1.75-2.68-.57-6.18-4.21-11.39-5-17.63-1-7.19-3.71-14.12-5.44-21.22A145.49,145.49,0,0,1,582,658.76a14.93,14.93,0,0,0-5.92-10.45c0-14.57,0-29.23,0-43.89q.07-52.24.15-104.49a21.49,21.49,0,0,0-.37-4.95c-.68-2.87-1.62-5.68-2.52-8.49a43.16,43.16,0,0,0-1.67-4.58c-2.17-4.8-6-8.3-9.65-11.89-2.33-2.27-4.61-4.57-7.34-7.28l9.39-2q1.33-8.75,2.63-17.12l6.7-2.33c-.61-7.9.19-15.58-.91-23.42-1.43-10.18-1.77-20.54-2.12-30.84-.58-17.64-.77-35.28-1.2-52.92-.1-4.13-.46-8.28-2.76-12.48l-27.12-2.36c-.67-1.39-1.87-3-2.27-4.88a61.49,61.49,0,0,1-1.73-11.32c-.58-17.79-.94-35.59-1.37-53.39,0-2,.09-4,0-6-.48-6.69-1.77-13.25-5.48-18.94a34.13,34.13,0,0,0-35.08-14.65c-2.76.52-5.71.75-8.17,1.94-11,5.37-18.62,13.7-20.84,26.13a59.69,59.69,0,0,0-1.11,10.89c.06,9.31.71,18.63.64,27.95-.08,10.65-.53,21.3-1.14,31.93-.23,3.9-1.37,7.74-2.13,11.85-10.7-.17-20.84.59-30.83,5v12.65c0,18.5.23,37,0,55.5-.14,10.42.95,20.84-.95,31.29-.94,5.14,0,10.63.15,16a10.51,10.51,0,0,0,.82,2.53l6.77,2.27c.21,4.65.41,9.21.63,13.95l10,.55a44.12,44.12,0,0,1-3,4.59,109.28,109.28,0,0,1-9.37,10.31c-4.32,3.88-6.26,8.83-7.85,14.13-.73,2.43-1.76,4.77-2.65,7.15-.55,13.69-.54,27.52-.55,41.35,0,2.33,0,4.66-.11,7q-.91,21.21-1.87,42.41c-.15,3.49-.55,7-.59,10.46-.09,8.83-.46,17.68.06,26.48.7,11.69,1.42,23.28-2.72,34.54a11.88,11.88,0,0,0-.38,1.45c-1.77,8.09-3.44,16.2-5.33,24.25-2.43,10.35-5.05,20.64-7.56,31a12.59,12.59,0,0,0-.25,2.46c-.35,9.31-.65,18.62-1,27.92-.4,9.63-.89,19.25-1.34,28.87a53.22,53.22,0,0,1-.35-8c.23-17.49.51-35,.68-52.45.11-10.81,0-21.62.09-32.43.05-5-.19-9.59-2.74-14.33-3.64-6.76-7.15-13.43-12.77-18.79a27.92,27.92,0,0,1-2.43-3.12l6.31-2.3c1.65-5.73-.32-11.87,1.31-17.42l6-2.81c-.18-7-.17-13.64-.57-20.24-.39-6.29-1.49-12.54-1.77-18.83-.57-12.64-.83-25.3-1.11-37.95-.08-3.48.54-7,.21-10.43-.81-8.6-1.68-17.15-.76-25.82.43-4-1.94-6.51-6.19-7-7.52-.8-15-2.78-22.79-1.37a81.55,81.55,0,0,1-2.37-25.47c.27-4.12.26-8.25.25-12.38,0-9.47-.43-19,0-28.41.22-5.35.44-10.62-.44-15.9C361.1,407,353.42,398.7,342.4,394c-11.65-5-23.48-2.64-33.75,4-9.12,5.93-14.19,15.15-14.72,26.27-.32,6.82,0,13.67-.13,20.5-.15,8.48-.26,17-.78,25.44-.39,6.29-1.72,12.52-2,18.8a32.25,32.25,0,0,1-3.77,12.77c-6.67.64-13.06,2.7-19.82,1.75-.32-7.75-.72-15-.9-22.34-.44-18.12-.94-36.25-1.1-54.37-.16-17.33.11-34.66.14-52a8.4,8.4,0,0,0-.68-3.33c-1.12-2.49-2.43-4.9-4-8-1.35-1.16-3.28-3-5.41-4.59-3.51-2.58-7.31-4.88-11.78-4.88-19,0-37.92,0-56.88.33-14.64.23-29.27,1.07-43.91,1.36-24,.48-48,.73-71.94,1.1-2,0-3.9.33-5.63.48-.62,1.49-.84,2.7-1.52,3.55-2.76,3.51-3.16,7.47-3.07,11.77.44,19.32.68,38.63,1,57.94.32,17.49.76,35,1,52.45.19,11.67.17,23.33.25,35,.06,8.33-.13,16.67.23,25,.72,16.45,2,32.88,2.59,49.34.88,26.62,1.3,53.25,2,79.88.29,11.12.84,22.24,1.18,33.36q.6,19.47,1,38.93c.37,18.31.58,36.62,1,54.93.17,8.51.43,17,.69,25.53H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"Dog" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M741.86,825c0-1.22.07-2.45.1-3.67.32-12.15.6-24.3.93-36.44.34-12.65.73-25.29,1.08-37.94q.49-17.72.95-35.44c.37-14.47.79-29,1.06-43.43.19-10.81,0-21.63.31-32.44.3-10.3,1.16-20.58,1.52-30.88.49-13.81.74-27.62,1.1-41.43.34-13.15.72-26.29,1-39.43.34-13.82.62-27.64,1-41.45.33-13.48.65-27,1-40.44.62-21.79,1.33-43.58,1.92-65.38.46-16.47.8-32.95,1.18-49.42.1-4.33,0-8.67.21-13,.51-11,1.18-21.91,1.7-32.87.36-7.65.57-15.3.88-22.95.7-16.8,1.44-33.59,2.12-50.39.39-9.48.58-19,1-28.44.55-11.3,1.47-22.58,1.87-33.88.19-5.15,0-10.54-6-13.07-.2-.09-.25-.54-.36-.83l2.43-2.05c-1.51-5.46-4.07-7.56-9.1-7.62l-86.41-1c-27.31-.32-54.62-.58-81.93-1-16.31-.23-32.61-.64-48.92-1q-26.46-.57-52.92-1.17a14.79,14.79,0,0,0-8.95,2.58c-3,2-6.11,3.89-9.59,6.09-.14.87-.29,2.17-.56,3.45-.23,1.1-.26,2.52-1,3.19-2.13,2-2.68,4.56-2.78,7.26s0,5.6,0,8.4c-.23,9.43-.65,18.86-.75,28.29-.18,17.16,0,34.33-.3,51.49-.33,18.64-1.24,37.27-1.53,55.9-.32,21.33-.24,42.66-.34,64,0,1.62,0,3.23,0,5.33l-33.75-4a17.66,17.66,0,0,1-.93-3.26c-1-9.58-2.72-19.17-2.8-28.76-.18-20.65.47-41.3.79-62,.06-3.83-.08-7.68.26-11.49a154,154,0,0,0-.1-27.91c-1.45-15.94-10.06-27.58-22-37.07-12.78-10.15-27.81-11-43.2-9.06-11.9,1.49-21.53,7.35-29.5,16.2A52.7,52.7,0,0,0,309,218.13c-1.11,13.42-1.36,26.92-1.92,40.38-.48,11.81-.74,23.64-1.35,35.44-.31,6-1.12,11.91-1.8,17.85-.65,5.61-1.28,11.24-2.18,16.82-1.25,7.67-2.74,15.31-4.15,23.11-1.7.14-3,.27-4.29.36-10.1.74-20.2,1.35-30.28,2.29-2,.18-3.84,1.56-6.17,2.57-3.23-1.06-6.25.36-7,3.85a35.89,35.89,0,0,0-.92,6.41c-.42,16.15-.9,32.29-1.08,48.44-.22,18.32.51,36.67-.43,54.94-.72,14.06.36,28.22-2,42.23-.63,3.65.48,7.58.48,11.38,0,6.12-.25,12.24-.4,18.32,2.84,4.49,8.49,3.77,12.2,6.05.27,7.43-.18,14.33.33,21.19l8.87,3.63c-4.79,5.42-9.85,9.93-13.69,15.67s-6.59,11.61-9.75,18.17a11.55,11.55,0,0,1,.41,3.14c-.34,4-1.19,7.91-1.25,11.87,0,2.32-2.11,5,.57,7.3l-3.29-.91c0,2.26,0,4.08,0,5.9q.22,45.44.47,90.88c.07,10.15.43,20.3.48,30.45.13,23,.18,46.09.28,69.14H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>',
		"DogPack" => '<svg width="0" height="0">
        <defs>
            <clipPath id="zuschnitt" clipPathUnits="objectBoundingBox" transform="scale(0.001,0.001)">
                <path class="set" d="M898.13,825q.3-30.19.75-60.38c.28-16.48.74-33,1.15-49.43.25-10.14.56-20.29.86-30.44.33-11,.72-22,1-32.93q.55-19.73,1-39.44c.35-15.64.74-31.28,1-46.93.46-30.63-.18-61.27,1.84-91.89,1.18-17.93.66-36,1.19-53.93.47-16.13,1.35-32.25,2-48.38a29,29,0,0,0-.29-3.88c-2.48-1.51-5.15-2.25-6.22-4-1.86-3-4.35-3.24-7.22-3.41q-10-.6-19.92-1.26Q855.62,357.39,836,356c-4.63-.31-9.27-.82-13.92-.85-13.66-.11-27.33-.25-41,.07-10.63.26-21.23,1.57-31.86,1.68-5.61.06-9.57,1.91-12.7,6.3a31.53,31.53,0,0,1-9.77,9.11c-3.57,2.11-5.9,5.19-5.82,9.78.16,10,.14,20,.11,29.94q-.15,44.5-.4,89c0,3.55-.58,7.09-.92,11-5-1.85-9.94-.38-14.75-3.42-.59-6.6-1.56-13.53-1.74-20.47-.34-13.63-.15-27.28-.36-40.92-.08-6,.24-12.14-1-17.91C696.64,406,675.28,396,653.62,404.26c-13.47,5.16-20.11,16.49-20.73,30.9-.49,11.46-.4,22.95-.76,34.42-.28,9-.69,18-1.39,26.92-.37,4.74-1.48,9.43-2.22,13.93-5.31.57-10.1,1.6-14.86,1.47-5.43-.16-10.56.14-15.88,3.28a36,36,0,0,0-.87,5.29q-.67,19.21-1.16,38.44c-.39,15-.7,30-1,45a9.25,9.25,0,0,1-.17,2.48c-2.32,7.16-1.32,14.54-1.51,21.83a31.54,31.54,0,0,0,.43,4.13l7.18,2.85v12.3l9.31,3.67c-1.2,1.52-2.26,3-3.43,4.32-2.83,3.25-5.64,6.54-8.6,9.67A23.61,23.61,0,0,0,592,677c-.8,4.05-.87,8.25-1.71,12.29-1.77,8.55-1.64,17.18-1.61,25.83,0,.89,0,1.79,0,2.69a16,16,0,0,1-2.43-6.48c-1.75-10.16-3.5-20.32-5.1-30.5-1.19-7.54-2.08-15.13-3.28-22.67-.77-4.79-.48-10-5.3-13.38-1.06-.74-1.44-2.94-1.53-4.5-.38-7.14-.62-14.3-.77-21.46q-.57-27.21-1-54.45c-.37-20.31-.95-40.63-.93-60.95a53.4,53.4,0,0,0-3.13-17.82,45.88,45.88,0,0,0-11-17c-1.44-1.47-3.43-2.6-3.91-4.71l6.68-3V446.33l6.79-3c-.26-7.08-.5-13.87-.77-20.66a11,11,0,0,0-.45-1.41,55.46,55.46,0,0,1-1.31-7.27c-.51-8.31-.78-16.63-1.2-24.94-.6-11.79-1.3-23.57-1.89-35.37-.39-7.81-.63-15.63-1-23.44a29.83,29.83,0,0,0-.91-4.43c-6.64-2.8-13.59-1.71-20.23-2.69a67.09,67.09,0,0,0-8.47-.21c-.61-2.1-1.2-3.62-1.49-5.2a245.35,245.35,0,0,1-3.81-37.17c-.38-13.15-.67-26.31-1.18-39.45a33.29,33.29,0,0,0-14.32-26.75c-3-2.19-6.78-3.48-10.34-4.79a31.47,31.47,0,0,0-27,2.21c-10.87,6.2-17.28,15.93-17.49,28.91-.13,8.33,0,16.66,0,25,0,15.81.62,31.65-1.21,47.42-.45,3.9-1.11,7.79-1.74,12.11-2.95.27-5.39.44-7.82.73-5.77.7-11.55,1.31-17.28,2.22-3.5.55-4.42,1.81-4.78,5.37a35.39,35.39,0,0,0-.14,4c.24,11.31.68,22.62.73,33.93.11,25.28,0,50.55,0,76.39l8.31,2.88V464l6.09,2.5c-2.59,3.55-4.7,6.93-7.29,9.89-4.49,5.13-6.55,11.1-7.06,17.75-.15,2,.23,4.21-.57,5.88-1.62,3.38-1.35,6.81-1.23,10.27.25,7.15.62,14.3.93,21.45.62,14.3,1.35,28.59,1.81,42.88.55,16.81.85,33.63,1.29,50.44.06,2.16.48,4.3.54,6.46.11,3.6.85,7.25-.43,10.84l-5.65,1.43c-2.83,12.87-5.36,25.74-8.53,38.44s-6.46,25.41-10.65,38.71c-.41-13.23-.91-25.49-1.11-37.76a35.1,35.1,0,0,0-4.85-16.58c-2.31-4.12-6-7.46-9.11-11.13-1.22-1.43-2.5-2.82-3.81-4.31l7.39-3.32c-.15-4.65-1.35-8.8.38-13l7.7-2.9c0-2.29.09-4.11,0-5.93-.8-13.44-1.95-26.86-2.41-40.31-.64-19.14-.79-38.3-1.15-57.44,0-2.17.06-4.35-.12-6.5s-.64-4.58-1-7c-9.14-4.53-19-3.62-28.83-4.41-1-6.12-2-11.84-2.76-17.59a54.11,54.11,0,0,1-.29-7c0-15.5,0-31-.1-46.5a67.76,67.76,0,0,0-1.05-11.9c-1.32-7-4.07-13.34-9.48-18.29-10.7-9.8-29.71-13.23-43.66-4.53-9.8,6.11-15.8,14.84-16,26.6-.36,21.47.4,43-2.09,64.36-.57,4.91-1.44,9.79-2.16,14.61l-4.44,1.58c0-1.84,0-3.16,0-4.47.19-12.82.49-25.65.56-38.47.16-27.66.16-55.32.32-83,0-4.95-.53-9.54-3.71-13.64-1.6-2.06-2.32-4.8-3.94-6.84-1.89-2.38-3.57-5.39-7.42-5.19-3.12.17-6.26.18-9.39.26-9,.22-17.94.66-26.91.63-25-.1-50-.39-75-.61l-27.69-.24c-.83,0-1.66.18-2.49.18-3.14,0-6.27,0-9.41,0H94c-1.35,2.47-3.27,4.78-3.89,7.4a48.36,48.36,0,0,0-.92,10.91c-.1,10.59,0,21.19,0,31.78q.07,76.71.69,153.43c.15,21.48.75,42.95,1.06,64.42.36,24.31.59,48.63,1,72.94.28,18.14.71,36.28,1,54.42q.56,30.45,1,60.91c0,2.83,0,5.65.06,8.48H0v175H1000V825Z" clip-rule="evenodd"></path>
            </clipPath>
        </defs>
    </svg>'
	];


	if (is_single(1171)) {
		echo $mask_array["FS"];
	} elseif (is_single("1173")) {
		echo $mask_array["BS"];
	} elseif (is_single("10681")) {
		echo $mask_array["HD"];
	} elseif (is_single("10682")) {
		echo $mask_array["ED"];
	} elseif (is_single("61378")) {
		echo $mask_array["small_ml"];
	} elseif (is_single('61399')) {
		echo $mask_array["FSPack"];
	} elseif (is_single("61679")) {
		echo $mask_array["BSPack"];
	} elseif (is_single('61684')) {
		echo $mask_array["EDPack"];
	} elseif (is_single("61688")) {
		echo $mask_array["HDPack"];
	} elseif (is_single("61698")) {
		echo $mask_array["Dog"];
	} elseif (is_single("61707")) {
		echo $mask_array["DogPack"];
	}
	
}
add_filter("woocommerce_after_single_product_summary", "svgmask",90, 1);

//do_action('apply_svg_mask_hook');
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	


	<div class="ast-row">
		<div class="ast-col-md-5 ast-col-xs-12 ast-col-md-push-6 image-wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );

			?>
		</div>

		<div class="ast-col-md-4 ast-col-xs-12 ast-col-md-pull-4">

			

			<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				
				if(is_single(61378)){
					add_action( 'woocommerce_before_add_to_cart_button', 'range_slider_small' );
				}
				 else{add_action( 'woocommerce_before_add_to_cart_button', 'range_slider' );}
				
				add_action('woocommerce_product_thumbnails', 'woocommerce_template_single_excerp');
				do_action( 'woocommerce_single_product_summary' );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_total_product_price', 25 ); ?>

		</div>
		


	</div>




	

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	

	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>



<script>
jQuery(function($){
	var price = <?php echo $product->get_price(); ?>,
		currency = '<?php echo get_woocommerce_currency_symbol(); ?>';

	$('[name=quantity]').change(function(){
		if (!(this.value < 1)) {

			var product_total = parseFloat(price * this.value);

			$('#product_total_price .price').html( currency + product_total.toFixed(0));

		}
	});
});
</script>
