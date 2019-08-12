(($) ->
  'use strict'

  # Make taxonomy checkboxes show and hide degrees.
  $update = ->
    $inputs = $ '#degree-filters input'
    $activeInputs = $inputs.filter ':checked'
    $degrees = $ '.degree'
    if $activeInputs.length is 0
      # Show all degrees
      $degrees.filter ':hidden'
        .fadeIn()
    else
      # Decide which degrees to show.
      activeInputClasses = []
      $activeInputs.each ( index ) ->
        activeInputClasses.push '.' + this.value
      selected = activeInputClasses.join ', '
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
  $reset = (e) ->
    e.preventDefault();
    $inputs = $ '#degree-filters input'
    $activeInputs = $inputs.filter ':checked'
    $activeInputs.each (i) ->
      $(this).prop 'checked', false
  $update()
  $('#degree-filters input').on 'change', $update
  $('.reset-degree-search').on 'click', $reset
  return
) jQuery
