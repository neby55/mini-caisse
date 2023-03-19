from django.http import JsonResponse, HttpResponse, HttpRequest, HttpResponseNotFound
from .models import Product, Order
from pprint import pprint

def products(request):
    products = Product.objects.all().values(*Product.API_ATTRIBUTES)
    data = list(products)
    return JsonResponse(data, safe=False)

def orders(request):
    orders = Order.objects.all().values(*Order.API_ATTRIBUTES)
    data = list(orders)
    return JsonResponse(data, safe=False)

def order(request, id=0):
    order = Order.objects.filter(id=id).values(*Order.API_ATTRIBUTES)[0]
    return JsonResponse(order, safe=False)

def createdOrders(request):
    orders = Order.objects.filter(status=Order.STATUS_CREATED).values(*Order.API_ATTRIBUTES)
    data = list(orders)
    return JsonResponse(data, safe=False)
    
def paidOrders(request):
    orders = Order.objects.filter(status=Order.STATUS_PAID).values(*Order.API_ATTRIBUTES)
    data = list(orders)
    return JsonResponse(data, safe=False)

def completedOrders(request):
    orders = Order.objects.filter(status=Order.STATUS_COMPLETED).values(*Order.API_ATTRIBUTES)
    data = list(orders)
    return JsonResponse(data, safe=False)
    
def setOrderPayment(request, id=0):
    if request.method == 'POST':
        try:
            order = Order.objects.get(pk=id)
        except Order.DoesNotExist:
            return HttpResponseNotFound('')
        
        if order.canSetPayment() == False:
            return HttpResponse('Order status incorrect', status=409)
        
        order.setPayment()
        return HttpResponse('Updated', status=200)
    else:
        return HttpResponseNotFound('')