<?php
namespace Mend;

class ApplicationConfigKey {
	const CONTEXT_TYPE = 'application:context.type';
	const CONTEXT_CHARSET = 'application:context.charset';
	const CONTEXT_VIEW_SUFFIX = 'application:context.view.suffix';
	const CONTEXT_CLASS = 'application:context.class';

	const CONTROLLER_FACTORY = 'application:controller.factory';
	const CONTROLLER_CLASS_FRONT = 'application:controller.class.front';
	const CONTROLLER_CLASS_NAMESPACES = 'application:controller.class.namespaces';
	const CONTROLLER_CLASS_SUFFIX = 'application:controller.class.suffix';

	const LAYOUT_PATH = 'application:layout.path';
	const LAYOUT_TEMPLATE_SUFFIX = 'application:layout.suffix';
	const LAYOUT_DEFAULT_TEMPLATE = 'application:layout.template.default';
	const LAYOUT_ENABLED = 'application:layout.enabled';

	const VIEW_PATH = 'application:view.path';
	const VIEW_TEMPLATE_SUFFIX = 'application:view.suffix';
}
