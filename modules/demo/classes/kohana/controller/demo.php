<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Controller_Demo extends Controller {

	/**
	 * @var  object  response view
	 */
	protected $view;

	/**
	 * @var  string  demo title
	 */
	protected $title;

	/**
	 * @var  string  demo content
	 */
	protected $content;

	/**
	 * @var  string  demo source code
	 */
	protected $code;

	public function before()
	{
		$this->view = View::factory('demo/template')
			->bind('title', $this->title)
			->bind('content', $this->content)
			->bind('code', $this->code)
			->bind('api', $this->api)
			->bind('apis', $apis)
			->bind('demo', $this->demo)
			->bind('demos', $demos)
			;

		if ($switch = Arr::get($_GET, 'switch-api-demo'))
		{
			// Switch to a different API
			$this->request->redirect($this->request->url(array('controller' => $switch, 'demo' => FALSE)));
		}

		// Build the APIs list
		$files = Kohana::list_files('classes/controller/demo');
		$apis  = array();

		foreach ($files as $file => $path)
		{
			if (preg_match('#^classes/controller/demo/(.+)'.preg_quote(EXT).'$#', $file, $matches))
			{
				// Extract the name of the API
				$name = $matches[1];

				// Add the API to the list
				$apis[$name] = ucwords(Inflector::humanize($name));
			}
		}

		// Set the demo controller
		$this->api = $this->request->controller();

		// Set the demo action
		$this->demo = $this->request->param('demo');

		// Start reflection to get the demo list
		$class   = new ReflectionClass($this);
		$methods = $class->getMethods();

		$demos = array();
		foreach ($methods as $method)
		{
			if (preg_match('/^demo_(.+)$/i', $method->name, $matches))
			{
				// Set the demo name
				$demo = $matches[1];

				// Add the demo link, but do not include index
				$demos[$demo] = $this->request->uri(array('demo' => strtolower($demo)));
			}
		}

		return parent::before();
	}

	public function action_show()
	{
		if ($this->demo)
		{
			// Start reflection
			$method = new ReflectionMethod($this, "demo_{$this->demo}");

			try
			{
				// Invoke the method to create content
				$method->invoke($this);

				// Load the source for this demo
				$this->view->code = $this->source();
			}
			catch (Exception $e)
			{
				// Start buffering
				ob_start();

				// Render the exception
				Kohana_Exception::handler($e);

				// Capture the exception HTML
				$this->content = ob_get_clean();
			}
		}
		else
		{
			// Load the index page for this demo
			$this->content = $this->index();
		}
	}

	public function index()
	{
		try
		{
			return View::factory("demo/{$this->api}/index");
		}
		catch (Kohana_View_Exception $e)
		{
			return View::factory("demo/index")
				->bind('api', $this->api)
				;
		}
	}

	public function after()
	{
		if ( ! $this->title)
		{
			// Set the page title to the api name
			$this->title = ucwords(Inflector::humanize($this->api));

			if ($this->demo)
			{
				// Add the demo name to the title
				$this->title .= ': '.ucwords(Inflector::humanize($this->demo));
			}
		}

		$this->response->body($this->view->render());

		return parent::after();
	}

	public function source()
	{
		// Start reflection of the current demo
		$method = new ReflectionMethod($this, "demo_{$this->demo}");

		// Do not include the function definition
		$start = $method->getStartLine() + 1;
		$end   = $method->getEndLine()   - 1;

		// Open the file for reading
		$handle = fopen($file = $method->getFilename(), 'r');

		// Starting line number
		$line = 0;

		$source = '';
		while ($row = fgets($handle))
		{
			if ($line++ < $start)
			{
				continue;
			}

			if ( ! isset($space))
			{
				// Find indentation whitespace of the first row
				preg_match('/^\s+/', $row, $matches);

				// Get the amount of space to find
				$space = isset($matches[0]) ? $matches[0] : FALSE;
			}

			if ($space)
			{
				if (substr($row, 0, strlen($space)) === $space)
				{
					// Remove indentation whitespace
					$row = substr($row, strlen($space));
				}
			}

			// Add this row to the source
			$source .= $row;

			if ($line >= $end)
			{
				break;
			}
		}

		// Source may include HTML, escape it
		$source = HTML::chars($source);

		// Set the source location
		$location = Kohana::debug_path($file);
		$location = "{$location} [ {$start} - {$end} ]";

		return "<aside>{$location}</aside>\n<pre><code>{$source}</code></pre>";
	}

} // End Demo
