<?php

namespace ACFWCA\handlebars;

/**
 * Renders a handlebars template from lib/templates.
 *
 * @param      array            $data      The data which will populate the template
 *
 * @return     string|boolean   The rendered HTML.
 */
function render_template( $data = [] ){
  if( empty( $data['template'] ) )
    return false;

  $phpStr = \LightnCandy\LightnCandy::compile( $data['template'], [
    'flags' => \LightnCandy\LightnCandy::FLAG_SPVARS | \LightnCandy\LightnCandy::FLAG_PARENT | \LightnCandy\LightnCandy::FLAG_ELSE
  ] );

  $renderer = eval( $phpStr );

  return $renderer( $data );
}
