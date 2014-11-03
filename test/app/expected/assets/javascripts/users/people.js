var People;

People = (function() {
  function People(name) {
    this.name = name;
  }

  People.prototype.echo = function() {
    return console.log(this.name);
  };

  return People;

})();
