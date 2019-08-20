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
      $activePrograms = $degrees.filter selected
      # Show or hide degrees.
      $activePrograms.fadeIn()
      $degrees.not selected
        .fadeOut()
    # if typeof e isnt 'undefined' and Foundation.MediaQuery.atLeast 'medium'
    #   $('#search-sidebar .sticky').foundation('_destroy')
    #   filters = new Foundation.Sticky( $('#search-sidebar > .wrap') )
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

  # Sticky search filters for mobile
  # if Foundation.MediaQuery.is 'small only'
  #   console.log $('.degree-search-toggle-container').outerHeight()
  #   $sidebar = $ '.degree-search-sidebar'
  #   $sidebar.find('> .wrap').removeClass('is-at-bottom').addClass('sticky')
  #   buttonHeight = $('.degree-search-toggle').outerHeight()
  #   console.log $('.degree-search-toggle')[0].getBoundingClientRect()
  #   $sidebar.find('#filter-wrap').css('top', buttonHeight + 'px')
  #   # Set bounds of filter box to allow scrolling
  #   $wrap = $sidebar.find('#filter-wrap')
  #   $filters = $wrap.find('#degree-filters')
  #   $filters.css('top', ($filters.offset().top - $wrap.offset().top + 16) + 'px')
  #   # Custom sticky script for mobile
  #   $(window).scroll (e) ->
  #     scroll = $(window).scrollTop()
  #     navheight = $('.site-header').height()
  #     sticky = $sidebar.find('> .wrap').removeClass('is-at-bottom')
  #     # if scroll > navheight
  #     sticky.css('top', (navheight + 16) + 'px')
  #     if sticky.hasClass('is-stuck') is false
  #       sticky.addClass('is-stuck').removeClass('is-anchored')
      # Fix issue where top position is set to a ridiculously high value on page load
  return
) jQuery
