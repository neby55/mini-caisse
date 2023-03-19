from django.urls import path

from . import views, endpoints

urlpatterns = [
    path('', views.index, name='index'),
    path('orders/filters/created', endpoints.createdOrders),
    path('orders/filters/paid', endpoints.paidOrders),
    path('orders/filters/completed', endpoints.completedOrders),
    path('orders/<int:id>/payment', endpoints.setOrderPayment),
    path('orders/<int:id>', endpoints.order),
    path('orders', endpoints.orders),
    path('products', endpoints.products),
]