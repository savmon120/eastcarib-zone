<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedTestError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* radix:node */
class __TwigTemplate_9ebdb90015859901f867ad04569d4347 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'node_title_prefix' => [$this, 'block_node_title_prefix'],
            'node_title' => [$this, 'block_node_title'],
            'node_title_suffix' => [$this, 'block_node_title_suffix'],
            'node_metadata' => [$this, 'block_node_metadata'],
            'node_content' => [$this, 'block_node_content'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--node"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:node"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:node"));
        // line 33
        $context["node_classes"] = Twig\Extension\CoreExtension::merge(["node", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 35
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 35)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--promoted") : ("")), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 36
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 36)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--sticky") : ("")), (( !(($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 37
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 37)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--unpublished") : ("")), \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 38
($context["node"] ?? null), "bundle", [], "any", false, false, true, 38)), ((\Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 39
($context["node"] ?? null), "bundle", [], "any", false, false, true, 39)) . "--") . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null))), ("node--" . \Drupal\Component\Utility\Html::getClass(        // line 40
($context["view_mode"] ?? null))), ((("node--" . \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 41
($context["node"] ?? null), "bundle", [], "any", false, false, true, 41))) . "--") . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null))), ("view-mode--" . \Drupal\Component\Utility\Html::getClass(        // line 42
($context["view_mode"] ?? null)))], (((($tmp =         // line 43
($context["node_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["node_utility_classes"]) : ([])));
        // line 45
        yield "
";
        // line 47
        $context["author_classes"] = Twig\Extension\CoreExtension::merge(["author"], (((($tmp =         // line 49
($context["author_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["author_utility_classes"]) : ([])));
        // line 51
        yield "
";
        // line 53
        $context["node_content_classes"] = Twig\Extension\CoreExtension::merge(["node__content"], (((($tmp =         // line 55
($context["node_content_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["node_content_utility_classes"]) : ([])));
        // line 57
        yield "
";
        // line 58
        $context["node_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 59
        yield "
";
        // line 60
        $context["wrapper_element"] = (((($tmp = ($context["page"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("div") : ("article"));
        // line 61
        yield "<";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["wrapper_element"] ?? null), "html", null, true);
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["node_attributes"] ?? null), "addClass", [($context["node_classes"] ?? null)], "method", false, false, true, 61), "html", null, true);
        yield ">
  ";
        // line 62
        yield from $this->unwrap()->yieldBlock('node_title_prefix', $context, $blocks);
        // line 65
        yield "
  ";
        // line 66
        yield from $this->unwrap()->yieldBlock('node_title', $context, $blocks);
        // line 78
        yield "
  ";
        // line 79
        yield from $this->unwrap()->yieldBlock('node_title_suffix', $context, $blocks);
        // line 82
        yield "
  ";
        // line 83
        yield from $this->unwrap()->yieldBlock('node_metadata', $context, $blocks);
        // line 97
        yield "
  <div ";
        // line 98
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [($context["node_content_classes"] ?? null)], "method", false, false, true, 98), "html", null, true);
        yield ">
    ";
        // line 99
        yield from $this->unwrap()->yieldBlock('node_content', $context, $blocks);
        // line 102
        yield "  </div>
</";
        // line 103
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["wrapper_element"] ?? null), "html", null, true);
        yield ">
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["node", "view_mode", "node_utility_classes", "author_utility_classes", "node_content_utility_classes", "attributes", "page", "content_attributes", "title_prefix", "label", "heading_html_tag", "title_link", "url", "title_attributes", "title_suffix", "display_submitted", "author_attributes", "author_picture", "author_name", "date", "content"]);        yield from [];
    }

    // line 62
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_node_title_prefix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 63
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_prefix"] ?? null), "html", null, true);
        yield "
  ";
        yield from [];
    }

    // line 66
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_node_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 67
        yield "    ";
        if ( !(($tmp = ($context["page"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 68
            yield "      ";
            // line 69
            yield from $this->load("radix:heading", 69)->unwrap()->yield(CoreExtension::merge($context, ["content" => ((            // line 70
array_key_exists("label", $context)) ? (Twig\Extension\CoreExtension::default(($context["label"] ?? null), "")) : ("")), "heading_html_tag" => ((            // line 71
array_key_exists("heading_html_tag", $context)) ? (Twig\Extension\CoreExtension::default(($context["heading_html_tag"] ?? null), "h2")) : ("h2")), "title_link" => ((            // line 72
array_key_exists("title_link", $context)) ? (Twig\Extension\CoreExtension::default(($context["title_link"] ?? null), ($context["url"] ?? null))) : (($context["url"] ?? null))), "heading_attributes" =>             // line 73
($context["title_attributes"] ?? null)]));
            // line 76
            yield "    ";
        }
        // line 77
        yield "  ";
        yield from [];
    }

    // line 79
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_node_title_suffix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 80
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_suffix"] ?? null), "html", null, true);
        yield "
  ";
        yield from [];
    }

    // line 83
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_node_metadata(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 84
        yield "    ";
        if ((($tmp = ($context["display_submitted"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 85
            yield "      <footer>
        <div ";
            // line 86
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["author_attributes"] ?? null), "addClass", [($context["author_classes"] ?? null)], "method", false, false, true, 86), "html", null, true);
            yield ">
          ";
            // line 87
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_picture"] ?? null), "html", null, true);
            yield "

          ";
            // line 89
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submitted by @author on @date", ["@author" =>             // line 90
($context["author_name"] ?? null), "@date" =>             // line 91
($context["date"] ?? null)]));
            // line 92
            yield "
        </div>
      </footer>
    ";
        }
        // line 96
        yield "  ";
        yield from [];
    }

    // line 99
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_node_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 100
        yield "      ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["content"] ?? null), "html", null, true);
        yield "
    ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:node";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  229 => 100,  222 => 99,  217 => 96,  211 => 92,  209 => 91,  208 => 90,  207 => 89,  202 => 87,  198 => 86,  195 => 85,  192 => 84,  185 => 83,  177 => 80,  170 => 79,  165 => 77,  162 => 76,  160 => 73,  159 => 72,  158 => 71,  157 => 70,  156 => 69,  154 => 68,  151 => 67,  144 => 66,  136 => 63,  129 => 62,  121 => 103,  118 => 102,  116 => 99,  112 => 98,  109 => 97,  107 => 83,  104 => 82,  102 => 79,  99 => 78,  97 => 66,  94 => 65,  92 => 62,  86 => 61,  84 => 60,  81 => 59,  79 => 58,  76 => 57,  74 => 55,  73 => 53,  70 => 51,  68 => 49,  67 => 47,  64 => 45,  62 => 43,  61 => 42,  60 => 41,  59 => 40,  58 => 39,  57 => 38,  56 => 37,  55 => 36,  54 => 35,  53 => 33,  49 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:node", "themes/contrib/radix/components/node/node.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 33, "block" => 62, "if" => 67, "include" => 69];
        static $filters = ["merge" => 43, "clean_class" => 38, "escape" => 61, "default" => 70, "t" => 89];
        static $functions = ["create_attribute" => 58];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "block", 2 => "if", 3 => "include"],
                [0 => "merge", 1 => "clean_class", 2 => "escape", 3 => "default", 4 => "t"],
                [0 => "create_attribute"],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            } elseif ($e instanceof SecurityNotAllowedTestError && isset($tests[$e->getTestName()])) {
                $e->setTemplateLine($tests[$e->getTestName()]);
            }

            throw $e;
        }

    }
}
