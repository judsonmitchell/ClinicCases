export const getModal = (id) => {
  return (
    bootstrap.Modal.getInstance(id, { keyboard: true }) ||
    new bootstrap.Modal(id)
  );
};
