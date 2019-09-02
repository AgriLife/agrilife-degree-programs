(($) ->
  'use strict'

  # Make taxonomy checkboxes show and hide degrees.
  $update = (e) ->
    $inputs = $ '#degree-filters input'
    $activeInputs = $inputs.filter ':checked'
    $items = $ '.degree'
    if $activeInputs.length is 0
      # Show all degrees
      $items.filter ':hidden'
        .fadeIn()
      $inputs.not ':enabled'
        .removeAttr 'disabled'
    else
      # Decide which degrees to show.
      activeInputClasses = []
      $activeInputs.each ( index ) ->
        activeInputClasses.push '.' + this.value
      selected = activeInputClasses.join ', '
      $activeItems = $items.filter selected
      # Show or hide degrees.
      $activeItems.fadeIn()
      $items.not selected
        .fadeOut()
      # Find which taxonomies are present in active degrees.
      activeTaxonomies = []
      taxonomies = /(degree-type|interest|department)-\S+/g
      $activeItems.each ->
        matches = this.className.match taxonomies
        j = 0
        while j < matches.length
          if matches[j] not in activeTaxonomies then activeTaxonomies.push '.' + matches[j]
          j++
      activeTaxonomies = activeTaxonomies.join ','
      # Change enabled state of filters.
      $inputs.filter activeTaxonomies
        .not ':enabled'
        .removeAttr 'disabled'
      $inputs.not activeTaxonomies
        .not ':disabled'
        .attr 'disabled', true
  $reset = (e) ->
    e.preventDefault();
    $inputs = $ '#degree-filters input'
    $activeInputs = $inputs.filter ':checked'
    $activeInputs.each (i) ->
      $(this).prop 'checked', false
    $update()
  $update()
  $('#degree-filters input').on 'change', $update
  $('.reset-search').on 'click', $reset
  return
) jQuery
