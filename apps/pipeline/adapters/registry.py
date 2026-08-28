from typing import Type
from apps.pipeline.adapters.base import BaseSourceAdapter
from apps.pipeline.adapters.ssc_adapter import SSCAdapter
from apps.pipeline.adapters.upsc_adapter import UPSCAdapter
from apps.pipeline.adapters.nta_adapter import NTAAdapter
from apps.pipeline.adapters.railway_adapter import RailwayAdapter
from apps.pipeline.adapters.generic_rss_adapter import GenericRSSAdapter
from apps.pipeline.adapters.generic_html_adapter import GenericHTMLAdapter

ADAPTER_REGISTRY = {
    'ssc': SSCAdapter,
    'upsc': UPSCAdapter,
    'nta': NTAAdapter,
    'railway': RailwayAdapter,
    'generic_rss': GenericRSSAdapter,
    'generic_html': GenericHTMLAdapter,
}

def get_adapter_class(parser_name: str) -> Type[BaseSourceAdapter]:
    return ADAPTER_REGISTRY.get(parser_name, GenericHTMLAdapter)

def get_adapter_instance(source) -> BaseSourceAdapter:
    adapter_cls = get_adapter_class(source.parser)
    return adapter_cls(source)
