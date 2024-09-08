from pydantic import BaseModel


class Material(BaseModel):
    title: str
    content: str
    class_level: str
    subject: str


class MaterialRequest(BaseModel):
    class_level: str
    subject: int
    task_type: int
    topic: str
    qty: int = None
    is_kk: bool = True



