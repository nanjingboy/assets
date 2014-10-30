class People
  constructor: (@name) ->

  echo: ->
    console.log @name

people = new People 'Tom'
people.echo()