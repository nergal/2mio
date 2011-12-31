/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Horizontal Page Break
 */

// Register a plugin named "similar".
CKEDITOR.plugins.add( 'similar',
{
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'similar', CKEDITOR.plugins.pagebreakCmd );

		// Register the toolbar button.
		editor.ui.addButton( 'Similar',
			{
				label : editor.lang.similar,
				command : 'similar'
			});

		// Add the style that renders our placeholder.
		editor.addCss(
			'img.cke_similar' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/similar.gif' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'clear: both;' +
				'display: block;' +
				'float: none;' +
				'width: 100%;' +
				'border-top: #999999 1px dotted;' +
				'border-bottom: #999999 1px dotted;' +
				'height: 5px;' +
				'page-break-after: always;' +

			'}' );
	},

	afterInit : function( editor )
	{
		// Register a filter to displaying placeholders after mode change.

		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter )
		{
			dataFilter.addRules(
				{
					elements :
					{
						div : function( element )
						{
							var attributes = element.attributes,
								style = attributes && attributes.style,
								child = style && element.children.length == 1 && element.children[ 0 ],
								childStyle = child && ( child.name == 'span' ) && child.attributes.style;

							if ( childStyle && ( /similar-after\s*:\s*always/i ).test( style ) && ( /display\s*:\s*none/i ).test( childStyle ) )
								return editor.createFakeParserElement( element, 'cke_similar', 'div' );
						}
					}
				});
		}
	},

	requires : [ 'fakeobjects' ]
});

CKEDITOR.plugins.similarCmd =
{
	exec : function( editor )
	{
		// Create the element that represents a print similar.
		var similarObject = CKEDITOR.dom.element.createFromHtml( '<div style="similar-after: always;"><span style="display: none;">&nbsp;</span></div>' );

		// Creates the fake image used for this element.
		similarObject = editor.createFakeElement( similarObject, 'cke_similar', 'div' );

		var ranges = editor.getSelection().getRanges();

		for ( var range, i = 0 ; i < ranges.length ; i++ )
		{
			range = ranges[ i ];

			if ( i > 0 )
				similarObject = similarObject.clone( true );

			range.splitBlock( 'p' );
			range.insertNode( similarObject );
			if ( i == ranges.length - 1 )
			{
				range.moveToPosition( similarObject, CKEDITOR.POSITION_AFTER_END );
				range.select();
			}
		}
	}
};
