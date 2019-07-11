(($) ->
  'use strict'

  # Make taxonomy checkboxes show and hide degrees.
  $update = ->
    $inputs = $ '#degree-filters input'
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
      $activeDegrees.each ->
        taxonomies = this.className.match /(degree-type|department|interest)-\S+/g
        j = 0
        while j < taxonomies.length
          if taxonomies[j] not in activeTaxonomies then activeTaxonomies.push '.' + taxonomies[j]
          j++
      activeTaxonomies = activeTaxonomies.join ','
      $inputs.filter activeTaxonomies
        .not ':enabled'
        .removeAttr 'disabled'
      $inputs.not activeTaxonomies
        .not ':disabled'
        .attr 'disabled', true
  $update()
  $('#degree-filters input').on 'change', $update
  # Open the degree search filter menus by default on medium and up.
  if Foundation.MediaQuery.atLeast 'medium'
    $('#degree-filters ul').addClass 'is-active'
  return
) jQuery
