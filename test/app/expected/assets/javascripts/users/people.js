(function() {
  var People, people;

  People = (function() {
    function People(name) {
      this.name = name;
    }

    People.prototype.echo = function() {
      return console.log(this.name);
    };

    return People;

  })();

  people = new People('Tom');

  people.echo();

}).call(this);
