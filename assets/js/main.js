/**
 * Containing class and regex.
 */
var param = {
  /**
   * Class to add to field depending if it's valid or not.
   */
  class: {
    valid: 'input-ok',
    error: 'input-error',
  },

  /**
   * Preset regex
   * Common regex for french forms.
   */
  reg: {
    /**
     * One or more char. Case insensitive. Accept:
     * - Letters
     * - Numbers
     * - ' ', '-', '_'.
     * @type {RegExp}
     */
    notEmpty: /^.+$/,

    /**
     * Three or more char. Case insensitive. Accept all char.
     * @type {RegExp}
     */
    name: /^.{3,}$/i,

    /**
     * For mail. Examples :
     * - mail-mail@domaine.com -> valid
     * - mail&mail@domain.com -> unvalid
     *
     * @type {RegExp}
     */
    mail: /^[a-z0-9._%-]+@[a-z0-9.-]+.[a-z]{2,4}$/i,

    /**
     * For french date
     * dd/mm/yyyy
     * @type {RegExp}
     */
    date: /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/,

    /**
     * For french zip code.
     * Five digits.
     * @type {RegExp}
     */
    zipCode: /^[0-9]{5}$/,

    /**
     * For french phone number.
     * 10 digits.
     * @type {RegExp}
     */
    phone: /^[0-9]{10}$/,
  }
};

/**
 * All form's fields goes here.
 */
var fields = {
  /*
   * Example field :
   *
   * fieldId: {
   *   reg: 'regularExpression'
   * },
   *
   * - 'fieldId:' MUST BE the id of the input field in DOM.
   * - 'reg:' CAN BE :
   *     - a regular expression directly written here,
   *     - a regular expression stocked in param.reg (which
   *       contain most common regex for french form).
   */
  lastName :{
    reg: param.reg.name,
  },

  firstName: {
    reg: param.reg.name,
  },

  emailAddr: {
    reg: param.reg.mail,
  },

  birthDate: {
    reg: param.reg.date,
  },

  address: {
    reg: param.reg.notEmpty,
  },

  city: {
    reg: param.reg.notEmpty,
  },

  postCode: {
    reg: param.reg.zipCode,
  },

  country: {
    reg: param.reg.notEmpty,
  },

  phoneNum: {
    reg: param.reg.phone,
  },
};

/**
 * Form object - Main app.
 */
var form = {
  /**
   * Set all listeners
   */
  init: function() {
    Object.keys(fields).forEach(function (field) {
      fields[field].dom = document.getElementById(field);
      if (fields[field].dom) {
        fields[field].dom.addEventListener('blur', form.controller);
      } else {
        console.error('Error : field named "' + field + '" does not match \
        with any DOM\'s id.');
      }
    });
  },

  controller: function() {
    if ( ! this.value) {
      form.displayDefault(this);
      return;
    }

    var inputId = this.getAttribute('id');
    var inputReg = fields[inputId].reg;
    var valid = form.checkThis(this.value, inputReg);

    console.info('Testing if ' , '"' + this.value + '"', ' match with "',
    inputReg + '"');

    if (valid) {
      form.displayValid(this);
      console.info('Matching !');
    } else {
      console.error('Not matching.');
      form.displayError(this);
    }
  },

  /**
   * Check if value match with regular expression
   * @return {Boolean} True if it match, false if it doesn't.
   */
  checkThis: function(value, reg) {
    return (value.match(reg)) ? 1 : 0;
  },

  /**
   * Add error class to field
   * Remove valid class if it exists.
   * @param  {Object} field Dom input
   */
  displayError: function(field) {
    field.classList.remove(param.class.valid);
    field.classList.add(param.class.error);
  },

  /**
   * Add error valid to field.
   * Remove error class if it exists.
   * @param  {Object} field Dom input
   */
  displayValid: function(field) {
    field.classList.remove(param.class.error);
    field.classList.add(param.class.valid);
  },

  /**
   * Remove error class and valid class of field.
   * @param  {Object} field Dom input
   */
  displayDefault: function(field) {
    field.classList.remove(param.class.error);
    field.classList.remove(param.class.valid);
  },
};

document.addEventListener('DOMContentLoaded', form.init);
