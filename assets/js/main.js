$('.input-error').focus(function() {
  $(this).removeClass('input-error');
});

/**
 * All form's parameters goes here
 */
var param = {
  /**
   * Class to add to field depending if it's valid or not.
   */
  'class': {
    'valid': 'input-ok',
    'error': 'input-error',
  },

  /**
   * Preset regex
   */
  'reg': {
    'name': /^[a-zA-z\-]{3,}$/,
    'mail': '',
  }
};

/**
 * All form's fields goes here.
 */
var fields = {
  'firstName': {
    'dom': null,
    'reg': param.reg.name,
  },

  'lastName' :{
    'dom': null,
    'reg': 'regular expression',
  }
};

/**
 * Form object
 * @type {Object}
 */
var form = {
  /**
   * Set all listeners
   */
  init: function() {
    for (var field in fields) {
      fields[field].dom = document.getElementById(field);
      fields[field].dom.addEventListener('blur', form.controller);
    }
  },

  /**
   * Get listener reg, and launch check.
   */
  controller: function() {
    if ( ! this.value) {
      return;
    }

    var inputId = this.getAttribute('id');
    var inputReg = fields[inputId].reg;
    console.info('Testing if ' , '"' + this.value + '"', ' match', inputReg);
    var valid = form.checkThis(this.value, inputReg);

    if (valid) {
      form.displayValid(this);
    } else {
      form.displayError(this);
    }
  },

  /**
   * Check if data math with regular expression
   * @return {Boolean} True if it match, false if it doesn't.
   */
  checkThis: function(value, reg) {
    return (value.match(reg)) ? 1 : 0;
  },

  displayError: function(field) {
    // this.className += 'input-error';
    field.classList.remove(param.class.valid);
    field.classList.add(param.class.error);
  },

  displayValid: function(field) {
    field.classList.remove(param.class.error);
    field.classList.add(param.class.valid);
  }
};

document.addEventListener('DOMContentLoaded', form.init);
