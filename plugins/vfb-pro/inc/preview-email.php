<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <style type="text/css">
			/* GLOBAL */
			* {
				font-family: [font-text];
				font-size: 14px;
				box-sizing: border-box;
				margin: 0;
				padding: 0;
			}

			img {
				max-width: 100%;
			}

			body {
				line-height: 1.6;
				width: 100% !important;
				height: 100%;

				-webkit-font-smoothing: antialiased;
				-webkit-text-size-adjust: none;
			}

			/* Make sure all tables have defaults */
			table td {
				vertical-align: top;
			}

			/* BODY & CONTAINER */
			body {
				background-color: [color-bg];
			}

			.body-wrap {
				width: 100%;
				background-color: [color-bg];
			}

			.container {
				display: block !important;
				/* makes it centered */
				clear: both !important;
				max-width: 700px !important;
				margin: 0 auto !important;
			}

			.content {
				display: block;
				max-width: 700px;
				margin: 0 auto;
				padding: 20px;
			}

			/* HEADER, FOOTER, MAIN */
			.main {
				border: 1px solid #e9e9e9;
				border-radius: 3px;
				background: #ffffff;
			}

			.content-wrap {
				padding: 20px;
			}

			.content-block {
				padding: 0 0 20px;
			}

			.header {
				width: 100%;
				margin-bottom: 20px;
			}

			.footer {
				clear: both;
				width: 100%;
				padding: 20px;
				color: #999999;
			}

			.footer a {
				color: [color-link];
			}

			.footer p,
			.footer a,
			.footer unsubscribe,
			.footer td {
				font-size: 12px;
			}

			/* TYPOGRAPHY */
			h1,
			h2,
			h3 {
				font-weight: 400;
				line-height: 1.2;
				margin: 40px 0 0;
			}

			h1 {
				font-family: [font-h1];
				font-size: 32px;
				font-weight: 500;
				color: [color-h1];
			}

			h2 {
				font-family: [font-h2];
				font-size: 24px;
				color: [color-h2];
			}

			h3 {
				font-family: [font-h3];
				font-size: 18px;
				color: [color-h3];
			}

			p,
			ul,
			ol {
				font-family: [font-text];
				font-weight: normal;
				margin-bottom: 10px;
				color: [color-text];
			}

			p li,
			ul li,
			ol li {
				margin-left: 5px;
				list-style-position: inside;
			}

			/* LINKS */
			a {
				text-decoration: underline;
				color: [color-link];
			}

			/* OTHER STYLES THAT MIGHT BE USEFUL */
			.last {
				margin-bottom: 0;
			}

			.first {
				margin-top: 0;
			}

			.aligncenter {
				text-align: center;
			}

			.alignright {
				text-align: right;
			}

			.alignleft {
				text-align: left;
			}

			.clear {
				clear: both;
			}

			/* FIELDS LIST */
			.fields {
				width: 80%;
				margin: 40px auto;
				text-align: left;
			}

			.fields td {
				padding: 5px 0;
			}

			.fields .field-items {
				width: 100%;
			}

			.fields .field-items td {
				border-top: #eeeeee 1px solid;
			}

			.fields .field-headings {
				color: [color-headings-text];
				background-color: [color-headings-bg];
			}

			.fields .field-headings td {
				font-weight: 600;
				padding: 5px;
			}

			/* RESPONSIVE AND MOBILE FRIENDLY STYLES */
			@media only screen and (max-width: 640px) {
				h1,
				h2,
				h3,
				h4 {
					font-weight: 600 !important;
					margin: 20px 0 5px !important;
				}

				h1 {
					font-size: 22px !important;
				}

				h2 {
					font-size: 18px !important;
				}

				h3 {
					font-size: 16px !important;
				}

				.container {
					width: 100% !important;
				}

				.content,
				.content-wrap {
					padding: 10px !important;
				}

				.fields {
					width: 100% !important;
				}
			}
        </style>
    </head>
    <body>
        <table class="body-wrap">
            <tr>
                <td></td>
                <td class="container" width="700">
                    <div class="content">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-wrap aligncenter">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="content-block" align="center">
                                                <h1>
                                                    [title]
                                                </h1>
                                            </td> <!-- .content-block -->
                                        </tr>
										<tr>
											<td class="content-block">
												[header-img]
											</td> <!-- .content-block -->
										</tr>
                                        <tr>
                                            <td class="content-block" align="center">
                                                <h2>
                                                    [message]
                                                </h2>
                                            </td> <!-- .content-block -->
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                <table class="fields">
                                                    <tr>
                                                        <td>
                                                            <table class="field-items" cellpadding="0" cellspacing="0">
                                                                [vfb-fields]
                                                            </table> <!-- .field-items -->
                                                        </td>
                                                    </tr>
                                                </table> <!-- .fields -->
                                            </td> <!-- .content-block -->
                                        </tr>
                                        <tr>
                                            <td class="content-block" align="center">
												[address]
											</td> <!-- .content-block -->
                                        </tr>
                                    </table>
                                </td> <!-- .content-wrap -->
                            </tr>
                        </table> <!-- .main -->

                        <div class="footer">
                            <table width="100%">
                                <tr>
                                    <td class="content-block aligncenter">[vfb-link-love]</td>
                                </tr>
                            </table>
                        </div> <!-- .footer -->
                    </div> <!-- .content -->
                </td> <!-- .container -->
                <td></td>
            </tr>
        </table> <!-- .body-wrap -->
    </body>
</html>
