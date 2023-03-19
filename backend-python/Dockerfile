FROM python:3.12-rc-alpine

RUN apk add --no-cache postgresql-libs

RUN apk add --no-cache --virtual .build-deps gcc musl-dev postgresql-dev

ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
WORKDIR /code
COPY requirements.txt /code/
RUN pip install --no-cache-dir -r requirements.txt
COPY . /code/