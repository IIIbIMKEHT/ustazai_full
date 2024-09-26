from sqlalchemy import Column, Integer, String

from services.database import Base


class User(Base):
    __tablename__ = "users"

    id = Column(Integer, primary_key=True, index=True)
    email = Column(String(100), unique=True, index=True)
    name = Column(String(50))
    count = Column(Integer())

    class Config:
        orm_mode = True
