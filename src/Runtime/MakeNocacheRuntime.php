<?php

namespace Smarty\Runtime;
use Smarty\Template;

/**
 * {make_nocache} Runtime Methods save(), store()
 *
 * @package    Smarty
 * @subpackage PluginsInternal
 * @author     Uwe Tews
 */
class MakeNocacheRuntime {

	/**
	 * Save current variable value while rendering compiled template and inject nocache code to
	 * assign variable value in cached template
	 *
	 * @param \Smarty\Template $tpl
	 * @param string $var variable name
	 *
	 * @throws \SmartyException
	 */
	public function save(Template $tpl, $var) {
		if (isset($tpl->tpl_vars[$var])) {
			$export =
				preg_replace('/^\\\\Smarty\\\\Variable::__set_state[(]|[)]$/', '', var_export($tpl->tpl_vars[$var], true));
			if (preg_match('/(\w+)::__set_state/', $export, $match)) {
				throw new SmartyException("{make_nocache \${$var}} in template '{$tpl->source->name}': variable does contain object '{$match[1]}' not implementing method '__set_state'");
			}
			echo "/*%%SmartyNocache:{$tpl->compiled->nocache_hash}%%*/<?php " .
				addcslashes("\$_smarty_tpl->smarty->getRuntime('MakeNocache')->store(\$_smarty_tpl, '{$var}', ", '\\') .
				$export . ");?>\n/*/%%SmartyNocache:{$tpl->compiled->nocache_hash}%%*/";
		}
	}

	/**
	 * Store variable value saved while rendering compiled template in cached template context
	 *
	 * @param \Smarty\Template $tpl
	 * @param string $var variable name
	 * @param array $properties
	 */
	public function store(Template $tpl, $var, $properties) {
		// do not overwrite existing nocache variables
		if (!isset($tpl->tpl_vars[$var]) || !$tpl->tpl_vars[$var]->nocache) {
			$newVar = new \Smarty\Variable();
			unset($properties['nocache']);
			foreach ($properties as $k => $v) {
				$newVar->$k = $v;
			}
			$tpl->tpl_vars[$var] = $newVar;
		}
	}
}
