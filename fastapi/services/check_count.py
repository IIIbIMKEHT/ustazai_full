from fastapi import HTTPException, Depends
from sqlalchemy.orm import Session

from models.user import User
from services.database import SessionLocal


# Зависимость для получения сессии
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


def get_user_by_email(email: str, db: Session = Depends(get_db)):
    user = db.query(User).filter(User.email == email).first()
    if user is None:
        raise HTTPException(status_code=404, detail="User not found")
    if user.count == 0:
        raise HTTPException(status_code=400, detail="Count is expired")
    user.count -= 1
    db.commit()
    return True
