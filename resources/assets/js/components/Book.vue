<template>
    <div id="canvas">

        <div id="book">
            <div depth="5" class="hard"></div>
            <div depth="5" class="hard front-side"><div class="depth"></div></div>
            <div class="hard fixed back-side p99"><div class="depth"></div></div>
            <div class="hard p100"></div>
        </div>

        <nav class="nav nav-pills nav-stacked book-nav">
            <li role="presentation"><a href="javascript:newPage()">New Page</a></li>
            <li role="presentation"><a href="javascript:dropBook()">Drop Book</a></li>
        </nav>

    </div>
</template>

<script>
    export default {
        mounted() {
            $(window).ready(function() {
                var flipbook = $('#book');

                //flipbook
            	flipbook.turn({
                    elevation: 50,
            		acceleration: true,
            		gradients: true,
            		autoCenter: true,
            		duration: 1000,
            		pages: 100,
            		when: {

                		turning: function(e, page, view) {

                			var book = $(this),
                			currentPage = book.turn('page'),
                			pages = book.turn('pages');

                			if (currentPage>3 && currentPage<pages-3) {
                				if (page==1) {
                					book.turn('page', 2).turn('stop').turn('page', page);
                					e.preventDefault();
                					return;
                				} else if (page==pages) {
                					book.turn('page', pages-1).turn('stop').turn('page', page);
                					e.preventDefault();
                					return;
                				}
                			} else if (page>3 && page<pages-3) {
                				if (currentPage==1) {
                					book.turn('page', 2).turn('stop').turn('page', page);
                					e.preventDefault();
                					return;
                				} else if (currentPage==pages) {
                					book.turn('page', pages-1).turn('stop').turn('page', page);
                					e.preventDefault();
                					return;
                				}
                			}
                            updateDepth(book, page);

                            if (page>=2)
            					$('#book .p2').addClass('fixed');
            				else
            					$('#book .p2').removeClass('fixed');

            				if (page<book.turn('pages'))
            					$('#book .p99').addClass('fixed');
            				else
            					$('#book .p99').removeClass('fixed');

                		},

                		turned: function(e, page, view) {

                			var book = $(this);

                            updateDepth(book);

                			book.turn('center');

                		},

                        end: function(e, pageObj) {

            				var book = $(this);

            				updateDepth(book);

            			},

                		missing: function (e, pages) {

                			for (var i = 0; i < pages.length; i++) {
                				addPage(pages[i], $(this));
                            }

                		}
                    }
            	}).turn('page', 2);

            	flipbook.addClass('animated');

                // Arrows
            	$(document).keydown(function(e){

            		var previous = 37, next = 39;

            		switch (e.keyCode) {
            			case previous:
            				$('#book').turn('previous');
                            break;
            			case next:
            				$('#book').turn('next');
                            break;
            		}

            	});

            })

            //add page to flipbook
            function addPage(page, book) {

            	var id, pages = book.turn('pages');

            	var element = $('<div />',
            			{'class': 'own-size',
            				css: {width: 450, height: 582}
            			});

            	if (book.turn('addPage', element, page)) {
            		if (page<=98) {
            			element.html('<div class="gradient"></div><div class="loader"></div>');
            			loadPage(page, element);
            		}
            	}

            }

            //book depth
            function updateDepth(book, newPage) {

            	var page = book.turn('page'),
            			pages = book.turn('pages'),
            			depthWidth = 16*Math.min(1, page*2/pages);

            	newPage = newPage || page;

            	if (newPage>3) {
            		$('#book .p2 .depth').css({
            			width: depthWidth,
            			left: 20 - depthWidth
            		});
            	} else {
            		$('#book .p2 .depth').css({width: 0});
            	}

            	depthWidth = 16*Math.min(1, (pages-page)*2/pages);

            	if (newPage<pages-3) {
            		$('#book .p99 .depth').css({
            			width: depthWidth,
            			right: 20 - depthWidth
            		});
            	} else {
            		$('#book .p99 .depth').css({width: 0});
            	}

            }
        }
    }
</script>
