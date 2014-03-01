<?php
namespace Application;

class ApplicationConfigKey {
	const LAYOUT_PATH = 'application:layout.path';
	const LAYOUT_TEMPLATE_SUFFIX = 'application:layout.suffix';
	const LAYOUT_DEFAULT_TEMPLATE = 'application:layout.template.default';
	const LAYOUT_ENABLED = 'application:layout.enabled';

	const VIEW_PATH = 'application:view.path';
	const VIEW_TEMPLATE_SUFFIX = 'application:view.suffix';

	const CONTROLLER_CLASS_MAIN = 'application:controller.class.main';
	const CONTROLLER_CLASS_NAMESPACES = 'application:controller.class.namespaces';
	const CONTROLLER_CLASS_SUFFIX = 'application:controller.class.suffix';
}