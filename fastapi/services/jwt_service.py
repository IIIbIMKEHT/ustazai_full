import os
from dotenv import load_dotenv
from fastapi import HTTPException
import jwt
from datetime import datetime, timedelta

load_dotenv()
SECRET_KEY = os.getenv('SECRET_KEY')


def create_jwt_token(email: str):
    expiration = datetime.utcnow() + timedelta(minutes=15)
    token = jwt.encode({"sub": email, "exp": expiration}, SECRET_KEY, algorithm="HS256")
    return token


def verify_jwt_token(token):
    try:
        jwt.decode(token, SECRET_KEY, algorithms=["HS256"])
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=403, detail="Token expired")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=403, detail="Invalid token")
