(($) ->
  'use strict'
  # Make taxonomy checkboxes show and hide degrees.

  $update = ->
    $inputs = $ 'input.degree-filter'
    $activeInputs = $inputs.filter ':checked'
    $degrees = $ '.degree'
    if $activeInputs.length is 0
      $degrees.filter ':hidden'
        .fadeIn()
      $inputs.not ':enabled'
        .removeAttr 'disabled'
    else
      # Decide which degrees to show.
      activeInputClasses = []
      $activeInputs.each ( index ) ->
        activeInputClasses.push '.' + this.value
      selected = activeInputClasses.join ''
      $activeDegrees = $degrees.filter selected
      # Show or hide degrees.
      $activeDegrees.fadeIn()
      $degrees.not selected
        .fadeOut()
      # Find which taxonomies are present in active degrees.
      activeTaxonomies = []
      console.log selected
      $activeDegrees.each ->
        taxonomies = this.className.match /(degree-type|department|interest)-\S+/g
        j = 0
        while j < taxonomies.length
          if taxonomies[j] not in activeTaxonomies then activeTaxonomies.push '.' + taxonomies[j]
          j++
      activeTaxonomies = activeTaxonomies.join ','
      console.log activeTaxonomies
      $inputs.filter activeTaxonomies
        .not ':enabled'
        .removeAttr 'disabled'
      $inputs.not activeTaxonomies
        .not ':disabled'
        .attr 'disabled', true
  $update()
  $('input.degree-filter').on 'change', $update
  return
) jQuery
