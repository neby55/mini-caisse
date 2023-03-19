from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone
import json

# Create your models here.
class Product(models.Model):
    API_ATTRIBUTES = ['id', 'name', 'price', 'color', 'status']
    
    STATUS_CREATED = 'created'
    STATUS_ENABLED = 'enabled'
    STATUS_DISABLED = 'disabled'
    STATUS_CHOICES = [
        (STATUS_CREATED, 'Created'),
        (STATUS_ENABLED, 'Enabled'),
        (STATUS_DISABLED, 'Disabled')
    ]
    
    name = models.CharField(max_length=64)
    price = models.DecimalField(decimal_places=2, max_digits=8)
    color = models.CharField(max_length=9)
    status = models.CharField(
        max_length=8,
        choices=STATUS_CHOICES,
        default=STATUS_CREATED
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    def __str__(self):
        return '#' + str(self.id) + ' ' + self.name + ' at ' + str(self.price)

class Order(models.Model):
    API_ATTRIBUTES = ['id', 'number', 'amount', 'payment_date', 'payment_mode', 'firstname', 'lastname', 'email', 'user', 'user__id', 'user__email', 'status']
    
    PAYMENT_CASH = 'cash'
    PAYMENT_CHEQUE = 'cheque'
    PAYMENT_CREDIT_CARD = 'credit card'
    PAYMENT_ONLINE = 'online'
    PAYMENT_CHOICES = [
        (PAYMENT_CASH, 'Cash'),
        (PAYMENT_CHEQUE, 'cheque'),
        (PAYMENT_CREDIT_CARD, 'credit card'),
        (PAYMENT_ONLINE, 'online')
    ]
    
    STATUS_CREATED = 'created'
    STATUS_PAID = 'paid'
    STATUS_COMPLETED = 'completed'
    STATUS_CANCELED = 'canceled'
    STATUS_CHOICES = [
        (STATUS_CREATED, 'Created'),
        (STATUS_PAID, 'Paid'),
        (STATUS_COMPLETED, 'Completed'),
        (STATUS_CANCELED, 'Canceled')
    ]
    
    number = models.PositiveIntegerField()
    amount = models.DecimalField(max_digits=8, decimal_places=2)
    payment_date = models.DateTimeField(null=True, blank=True)
    payment_mode = models.CharField(
        null=True,
        blank=True,
        max_length=12,
        choices=PAYMENT_CHOICES
    )
    firstname = models.CharField(null=True, blank=True, max_length=128)
    lastname = models.CharField(null=True, blank=True, max_length=128)
    email = models.EmailField(null=True, blank=True)
    user = models.ForeignKey(User, on_delete=models.SET_NULL, null=True, blank=True)
    status = models.CharField(
        max_length=9,
        choices=STATUS_CHOICES,
        default=STATUS_CREATED
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    def __str__(self):
        return '#' + str(self.number)
    
    def canSetPayment(self):
        return self.status == self.STATUS_CREATED
    
    def setPayment(self):
        self.payment_date = timezone.now()
        self.status = self.STATUS_PAID
        return self.save()
    
    def canSetCompleted(self):
        return self.status == self.STATUS_PAID
    
    def setCompleted(self):
        self.status = self.STATUS_COMPLETED
        return self.save()

class Cart(models.Model):
    API_ATTRIBUTES = ['id', 'order_id', 'product_id', 'price', 'quantity']
    
    order = models.ForeignKey(Order, on_delete=models.DO_NOTHING)
    product = models.ForeignKey(Product, on_delete=models.DO_NOTHING)
    quantity = models.PositiveSmallIntegerField()
    price = models.DecimalField(decimal_places=2, max_digits=8)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    def __str__(self):
        return 'Order #' + str(self.order.number) + ' ' + self.product.name + ' x' + str(self.quantity)
    