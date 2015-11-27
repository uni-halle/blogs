#!/bin/bash

cd /Users/dan/git/PDFEmbedder/

# php ../i18n-tools/add-textdomain.php -i pdf-embedder basic_pdf_embedder.php
# php ../i18n-tools/add-textdomain.php -i pdf-embedder premium_mobile_pdf_embedder.php
# php ../i18n-tools/add-textdomain.php -i pdf-embedder core/commercial_pdf_embedder.php
# php ../i18n-tools/add-textdomain.php -i pdf-embedder core/core_pdf_embedder.php

php ../i18n-tools/makepot.php wp-plugin . lang/orig_pdf-embedder.pot
cat lang/orig_pdf-embedder.pot lang/pot_extra.pot > lang/pdf-embedder.pot
