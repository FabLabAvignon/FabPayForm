<?php
/**
 * Hi
 *
 * @todo Write real code
 * @todo Write example file
 */

 /**
  *
  * Abstract EzForm class. Shall mainly be used to validate a form.
  *
  * @license MIT
  * @author Gregoire Lodi <gregoire.lodi@gmail.com> https://github.com/lodi-g
  * @version 0.0.1
  */
abstract class EzForm
{

  /**
   * Contains all the fields with their names and defined parameters.
   *
   * @var array
   *
   * @example ["age" => ["mandatory" => true, "regex" => "/[0-9]{0,2}/"]]
   */
  private $fields;


  /**
   * Constructs an EzForm object.
   *
   * @param array $fields Will use the addFields method.
   *
   * @return true false
   *
   * @example
   */
  public function __construct(array $fields = [])
    {
      return $this->addFields($fields);
    }


  /**
   * Validates a set of rules defined previously against a $data array
   * Returns an array which is either [true] or [false, ...] where
   * ... is the invalid fields with their invalid parameters.
   *
   * @param array $data data to validate against what was registered
   *
   * @return array Arrays that indicates if the form is valid or not.
   *
   * @example
   */
  final public function validate(array $data) : array
    {
      return [true];
    }


  /**
   * Adds a single field with the given parameters.
   *
   * @param string $fieldName Field's name
   * @param array  $params    Field's parameters. If omitted, defaulted to [].
   *
   * @return true false
   *
   * @example
   */
  final public function addField(string $fieldName, array $params = [])
    {
      return addFields([$fieldName, $params]);
    }


  /**
   * Adds multiple fields with their given parameters.
   *
   * @uses \EzForm\EzForm::$fields
   *
   * @param array $fields an array containing all fields and their parameters.
   *
   * @return true false
   *
   * @example
   */
  final public function addFields(array $fields) : bool
    {
      return true;
    }


  /**
   * Updates the given field with the given parameter. If the parameter does
   * not exist, it will be created.
   *
   * @param string $field
   * @param string $param
   * @param string|bool $value [description]
   *
   * @return true false
   *
   * @example
   */
  final public function updateFieldSingle(
        string $field,
        string $param,
        $value) : bool
    {
      return $this->updateFieldMultiple($field, [$param => $value]);
    }


  /**
   * Updates the given field with the given parameters. If parameters does not
   * exist, they will be created.
   *
   * @param string $field  [description]
   * @param array  $params [description]
   *
   * @return true false
   *
   * @example
   */
  final public function updateFieldMultiple(
        string $field,
        array $params) : bool
    {
      return true;
    }
}
