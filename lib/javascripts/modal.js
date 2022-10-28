export const getModal = (id) => {
  return (
    bootstrap.Modal.getInstance(id) ||
    new bootstrap.Modal(id)
  );
};
